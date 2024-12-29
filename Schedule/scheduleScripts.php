<script>
//document.addEventListener("DOMContentLoaded", init);
      //globals for api
      var map, geocoder, markers = [];
      //misc globals
      var scheduling = false,
         laundromats = [],
         userLoc,
         tripDisplay = false,
         tripStatus = "none",
         prevLat = 0, prevLng = 0;



      function init(){
         let isDriver = false,
               loggedIn = true;
         //change page view if user is a driver or not or nog logged in
         <?php if($provider): ?>
            isDriver = true;
         <?php endif; ?>
         <?php if($provider == null && $user == null): ?>
            //not logged in;
            loggedIn = false;
         <?php endif; ?>

         if(!loggedIn){
            initMap();
            document.getElementById("status").innerHTML = "Please <a href='/login/login.php'>Log in</a> to an account to schedule laundry pick up!";
            return;
         }
         
         if(isDriver){
            //check if driver already has an active trip
            getActiveTripDriver();
            initMap();
         } else {
            //check if user already has an active trip
            initMap();
           getActiveTripUser();
         }

         //one time event listeners
         document.getElementById("instructions").addEventListener("submit", e => {
            e.preventDefault(); 
            document.getElementById("instructions").style.visibility = "hidden";
            let instructions = getInstructions();
         });
         

         
      }

      async function initMap(){
         const { Map } = await google.maps.importLibrary("maps");
         
         const { AdvancedMarkerElement, PinElement } = await google.maps.importLibrary("marker");
         geocoder = new google.maps.Geocoder();
         map = new Map(document.getElementById("map"), {
            center: { lat: -34.397, lng: 150.644 },
            zoom: 14,
            mapId: "demoMapID",
            disableDefaultUI: "true",
         });
         //try to get location
         if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
               (position) => {
                  const pos = {
                     lat: position.coords.latitude,
                     lng: position.coords.longitude,
                  };
                  userLoc = pos;
                  //style marker with an icon
                  const icon = document.createElement("div");
                  icon.innerHTML = "<i class ='fa fa-home fa-lg'></i>";
                  const pin = new PinElement({
                     glyph: icon
                  })
                  const marker = new AdvancedMarkerElement({
                     map: map,
                     position: pos,
                     content: pin.element
                  });
                  //add styling to marker
                  map.setCenter(pos);
                  map.setZoom(14);
               },
               (err) => {
                  console.log("error", err);
               },
               { enableHighAccuracy: true }
            );
         } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
         }
      }

      function findClient(){
         let dateTime = new Date().toLocaleString();
         //add to active clients
         data = new FormData();
         data.append ("driverLat", userLoc.lat);
         data.append ("driverLng", userLoc.lng);
         data.append ("dateTime", dateTime);
         fetch("./toggleActiveDriver.php", {
            method: 'post',
            body: data
         }).then(res => res.text()).then( res => {

            if(res == "ACTIVE"){
               document.querySelector("#status").innerHTML = "Waiting for a client <img id='loadinggif' src=https://upload.wikimedia.org/wikipedia/commons/7/7a/Ajax_loader_metal_512.gif>";
                  getTripLoop("driver");
            } else {
               //change status
               document.querySelector("#status").innerHTML = "No Active Trip";

            }
         });

      }

      function getTripLoop(userType){
         //different functions depending on if a driver is logged in or a user is logged in
         //driver will always try to get an active trip
         //user will keep checking for updates and changing their display; 
         //user will need constant
         if(userType == "driver"){
               //get active trip
               fetch("./getActiveTripDriver.php", {
               method: 'post'
            }).then(res => res.json()).then( res => {
               if(res == "NONE"){
                  //continue loopin
                  setTimeout(function () {
                     getTripLoop(userType);
                  }, 10000);
               } else { //active trip found, keep looping to update position
                  getActiveTripDriver();
                  //userType = "finished";
                  updateDriverPosition();
               }
            });
               
         } else if(userType == "user"){ /////////////////////////////////////////////
            setTimeout(function () {
            getActiveTripUser();
            
         }, 10000)
         }

      }

      function updateDriverPosition(){
         //first get position from geolocation
         if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
               (position) => {
                  //update prev pos
                  prevLat = position.coords.latitude,
                  prevLng = position.coords.longitude;
                  //now update driver position in driver_location if not booked and activetrips if booked
                  let data = new FormData();
                  data.append('lat', position.coords.latitude);
                  data.append('lng', position.coords.longitude);
                  fetch('./updateDriverPosition.php', {
                        method: 'post',
                        body: data
                     }).then( res => res.text()).then(res => {
                        if (res == "SUCCESS"){
                           
                        } else {
                           console.log("error updating driver pos: ", res)
                        }
                     });
               },
               (err) => {
                  console.log("error", err);
               },
               { enableHighAccuracy: true }
            );
         } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
         }
      }



      function handleScheduleSubmit(e){
         //form validation
         if(document.getElementById("scheduledate").value == "" || document.getElementById("scheduletime").value == ""){
            alert("schedule requires a date and time!");
            return;
         }
         //hide popup
         e.target.parentElement.style.visibility = "hidden";
         //search for laundromats
         scheduling = true;
         laundromatSearch();
      }

      function laundromatSearch(){
         //change status
         document.getElementById("status").textContent = "Selecting Laundromat..."
         
         //get laundromats from user location
         navigator.geolocation.getCurrentPosition(
            (position) => {
               const pos = {
               lat: position.coords.latitude,
               lng: position.coords.longitude,
               };
               getLaundromats(pos);
            },
            (err) => {
               console.log("geolocation error ", err)
               
            }, 
            {enableHighAccuracy: true}
            // () => {
            //    //if fail to get location
            //    alert("cannot function without user location! please allow location services.");
            //    return;
            // }
            
         );
      }

      function handleLaundryButton(e){
         scheduling = false;
         //hide buttons 
         // document.getElementById("getlaundry").style.visibility = "hidden";
         // document.getElementById("schedulelaundry").style.visibility = "hidden";
         document.querySelector(".customerbuttons").style.display = "none";
         laundromatSearch();
      }

      function handleScheduleLaundry(e){
         //bring up schedule form
         document.getElementById("schedulepopup").style.visibility = "visible";
         //
      }

      function displayAdvancedMarker(pos, content){
         marker = new google.maps.marker.AdvancedMarkerElement({
            map,
            position: pos,
            title: content
         });
         createListener(marker);
         markers.push(marker);
         //bring up an infowindow on marker click
            
      }



      function getLaundromats(pos){
         //first get nearby laundromats
         request = {
            location: pos,
            radius: 50000,
            type: ['laundry']
         };
         service = new google.maps.places.PlacesService(document.createElement('div'));
         service.nearbySearch(request, displayLaundromats);

         //then add a marker on the map for those laundromats
      }

      function displayLaundromats(searchJSON, status, pagination){
         //handle errors
         if (status.toLowerCase() != "ok"){
            console.log("error", status);
         } 
         //if no laundromat found
         if(searchJSON.length == 0) {
            alert("no laundromat found! uh oh")
            return;
         }

         //create markers of first 5 laundromats in list
         for(let i=0;i<5;i++){
            geocode({ address: searchJSON[i].vicinity}, searchJSON[i].name);
         }
         //add laundromats to menu selection
         createLaundromatsMenu(searchJSON);
         //make instructions visible
         document.getElementById("instructions").style.visibility = "visible";
         
      }

      function createLaundromatsMenu(JSON){
         document.getElementById("laundromats").style.display = "inline-block";
         //empty menu and create a container for each laundromat found out of 5
         document.querySelector("#laundromats").innerHTML = ``;
         html = ``;
         for(let i=0;i<5;i++){
            html += `
            <div data-num=${i}>
               <h2>${JSON[i].name}</h2>
               <p>${JSON[i].vicinity}</p>
            
            </div>
            `
//${JSON[i].photos[0].html_attributions[0]}
         }
         document.querySelector("#laundromats").innerHTML += html;
         //add event listeners to divs for selection functionality
         document.querySelectorAll("#laundromats div").forEach(item => {item.addEventListener("click", selectLaundromat)});

      }

      function selectLaundromat(e){
         //get selected laundromat
         let name = e.target.parentElement.querySelector("h2").textContent;
         let clocation = getLaundromatLocationFromName(name);

         //close laundry menu
         //document.querySelector("#laundromats").innerHTML = "";

        

         //zoom in on laundromat
         map.setZoom(13);
         map.setCenter(clocation);

         //set infowindow for selected laundromat
         for(let i=0;i<5;i++){
            if(markers[i].title == name){
               createInfoWindow(markers[i]);
            }
         }

         //set timer on text and wait 1 
         //setInterval(animateGetDriverText, 500);

         //find driver
         // setTimeout(function (){
         //    getDriver();
         // }, 3000);
         
      }

      function finalizeLaundromat(e){
         document.getElementById("laundromats").style.display = "none";
         let name = e.target.getAttribute("data-title"),
             locPos = getLaundromatLocationFromName(name);
         //get selected laundromat info
         let laundromatData = {
            name: name,
            lat: locPos.lat(),
            lon: locPos.lng()
         }
          //change status to finding driver
         document.querySelector("#status").innerHTML = "Finding a Driver <img id='loadinggif' src=https://upload.wikimedia.org/wikipedia/commons/7/7a/Ajax_loader_metal_512.gif>";
         //find driver after second delay
            setTimeout(function (){
            getDriver(laundromatData);
         }, 1000);
      }

      function animateGetDriverText(){
         let statusElm = document.getElementById("status");
         let textContent = statusElm.textContent;
         
         if(textContent == "Getting Driver..."){
            statusElm.textContent = "Getting Driver.";
         } else if(textContent == "Getting Driver."){
            statusElm.textContent == "Getting Driver..";
         } else {
            statusElm.textContent = "Getting Driver...";
         }
      }

      function getDriver(laundromatData){
         //get closest driver
         data = new FormData();
         data.append('lat', userLoc.lat);
         data.append('lon', userLoc.lng);
         fetch('./getNearbyDrivers.php', {
                method: 'post',
                body: data
            }).then( res => res.json()).then(res => {
                if(res == "NOITEM"){
                    alert("No nearby drivers were found.");
                } else {
                    //now select the closest driver and display it
                    //console.log("item", res);
                    displayDriver(res, laundromatData);
                }
            });
      }

      function displayDriver(res, laundromatData){
         //create html element and add it 
         let html = `<h2>${res[0].first_name_or_business_name}</h2>`
         html += createStars(4.5, "star");
         html += `<img src="${res[0].profile_picture}">`
         document.getElementById("driverdiv").innerHTML = html;
         document.getElementById("driverdiv").style.visibility = "visible";
         document.getElementById("status").textContent = "Driver Found! Is on their way";


         //now that all of the info is available (user, driver, laundromat) add an activeTrip to the db
         addActiveTrip(res[0], laundromatData);
      }

      function addActiveTrip(driverData, laundromatData){
         let data = new FormData(),
            instructions = getInstructions();
         //user data can be read from the session data (currently logged in user) and current loc
         //get user id and name
         <?php if($user): ?>
            data.append("userName", "<?php echo ($user["first_name"])?>");
            data.append("userId", "<?php echo $_SESSION['user_id']?>");
         <?php endif ?>

         data.append("userLat", userLoc.lat);
         data.append("userLng", userLoc.lng);
         //driver data is in the json object passed in to this function
         //console.log("driver ", driverData);
         data.append("driverName", driverData.first_name_or_business_name);
         data.append("driverLat", driverData.lat);
         data.append("driverLng", driverData.lng);
         data.append("driverId", driverData.id);
         //laundromat location is in a json object passed into this function
         data.append("laundromatName", laundromatData.name);
         data.append("laundromatLat", laundromatData.lat);
         data.append("laundromatLng", laundromatData.lon);

         data.append("instructions", JSON.stringify(instructions));
         fetch('./addActiveTrip.php', {
            method: 'post',
            body: data
         }).then(res => res.text()).then(res => {
            if(res == "SUCCESS"){
               //begin triploop
               getTripLoop("user");
            } else {
               alert("already has an active trip");
            }
         })
      }

      function getActiveTripDriver(){
         fetch('getActiveTripDriver.php', {
            method: 'post'
         }).then(res => res.json()).then(res => {
            if(res == "NONE"){
               document.querySelector(".driverbuttons").style.display = "flex";
               document.getElementById("status").innerHTML = "Currently inactive"
               document.getElementById("startshift").addEventListener("click", findClient);
               getUnratedTrip();
            } else {
               //create UI if trip status or driverposchanges

               if(res.status != tripStatus || res.driverLat != prevLat || res.driverLng != prevLng){

                  tripStatus = res.status;
                  displayTrip(res, "driver");
               }
               //loop 
               setTimeout(function () {
                     getTripLoop("driver");
               }, 10000);
            }
         })
         //return false;
      }

      function getActiveTripUser(){
         fetch('getActiveTripUser.php', {
            method: 'post'
         }).then(res => res.json()).then(res => {
            if(res == "NONE"){
               //reset page view
               document.querySelector(".customerbuttons").style.display = "flex";
               document.querySelector("#driverdiv").style.display = "none";
               document.querySelector("#status").innerHTML = "Schedule Laundry Pick Up";
               initMap();
               //event handlers
               document.querySelector("#getlaundry").addEventListener("click", handleLaundryButton);
               document.querySelector("#schedulelaundry").addEventListener("click", handleScheduleLaundry);
               document.querySelector("#schedulesubmit").addEventListener("click", handleScheduleSubmit);
               document.querySelector(".delbutton").addEventListener("click", function(e) {
                     e.target.parentElement.style.visibility = "hidden";
               });
               getUnratedTrip();
            } else {
               //create UI
               getTripLoop("user");
               document.querySelector(".customerbuttons").style.display = "none";
               //create UI if trip status changes
               if(res.status != tripStatus || res.driverLat != prevLat || res.driverLng != prevLng){
                  tripStatus = res.status;
                  displayTrip(res, "user");
               }
            }
         })
      }

      function displayTrip(res, accountType){
         //display specific UI element for drivers and users

         let html;
         if(accountType == "driver"){
            let html = `<h2>${res.userName}</h2>`
            if(res.ratingsNum == 0 || res.totalStars == 0){
               html += createStars(0, "star");
            } else {
               html += createStars(res.totalStars / res.ratingsNum, "star");
            }
            html += `<img src="../${res.otherProfile}"></img>`
            document.getElementById("driverdiv").innerHTML = html;
            document.getElementById("driverdiv").style.visibility = "visible";
            document.getElementById("driverdiv").style.display = "block";
            let driverButtons = document.querySelector(".driverbuttons");
            //sort by status (pickup -> washinging -> dropoff)
            switch(res.status){
               case "pickup":
                  document.getElementById("status").textContent = "Currently picking up " + res.userName + "'s laundry";
                  
                  driverButtons.innerHTML = `<button id="status2" class="btn btn-dark">Now Washing Laundry</button><button id="cancelTrip" class="btn btn-dark">Cancel</button>`;
                  driverButtons.style.display = "flex";
                  document.getElementById("status2").addEventListener("click", updateDriverStatus);
                  document.getElementById("cancelTrip").addEventListener("click", updateDriverStatus);
                  break;
               case "washing":
                  document.getElementById("status").textContent = "Currently cleaning " + res.userName + "'s laundry at " + res.laundromatName + "";                 
                  driverButtons.innerHTML = `<button id="status3" class="btn btn-dark">Now Dropping Off</button><button id="cancelTrip" class="btn btn-dark">Cancel</button>`;
                  driverButtons.style.display = "flex";
                  document.getElementById("status3").addEventListener("click", updateDriverStatus);
                  document.getElementById("cancelTrip").addEventListener("click", updateDriverStatus);
                  break;
               case "dropoff":
                  document.getElementById("status").textContent = "Currently dropping off " + res.userName + "'s laundry";
                  driverButtons.innerHTML = `<button id="finished" class="btn btn-dark">Finished Returning Laundry</button>`;
                  driverButtons.style.display = "flex";
                  document.getElementById("finished").addEventListener("click", updateDriverStatus);
                  break;
            }
         } else {
            //user view
            let html = `<h2>${res.driverName}</h2>`
            if(res.ratingsNum == 0 || res.totalStars == 0){
               html += createStars(0, "star");
            } else {
               html += createStars(res.totalStars / res.ratingsNum, "star");
            }
            
            html += `<img src="../${res.otherProfile}"></img>`
            
            document.getElementById("driverdiv").innerHTML = html;
            document.getElementById("driverdiv").style.visibility = "visible";
            document.getElementById("driverdiv").style.display = "block";
            //sort by status
            switch(res.status){
               case "pickup":
                  document.getElementById("status").textContent = "" + res.driverName +  " is on their way to pick up your laundry";
     
                  break;
               case "washing":
                  document.getElementById("status").textContent = "" + res.driverName +  " is currently cleaning your laundry at " + res.laundromatName + "";
                  break;
               case "dropoff":
                  document.getElementById("status").textContent = "" + res.driverName +  " is on their way to drop off your laundry";
                  break;
            }
         }

         //display user, driver, and laundromat locations on the map
         let userPos = new google.maps.LatLng(res.userLat, res.userLng),
            driverPos = new google.maps.LatLng(res.driverLat, res.driverLng),
            laundromatPos = new google.maps.LatLng(res.laundromatLat, res.laundromatLng);
         //hide laundromat markers before displaying route
         calcRoute(userPos, driverPos, laundromatPos, res.status);
      }


      function calcRoute(userPos, driverPos, laundromatPos, tstatus) {
         for(let i=0;i<markers.length;i++){
            markers[i].setMap(null);
         }
         var directionsService = new google.maps.DirectionsService();
         var mydirectionsRenderer = new google.maps.DirectionsRenderer({
            preserveViewpoint: false
         });
         
 
         mydirectionsRenderer.setMap(map);
         // var start = document.getElementById('start').value;
         // var end = document.getElementById('end').value;
         var request;
         switch (tstatus){
            case "pickup":
               var request = {
                  origin: driverPos,
                  destination: userPos,
                  travelMode: 'DRIVING'
               };
               break;
            case "washing":
               var request = {
                  origin: driverPos,
                  destination: laundromatPos,
                  travelMode: 'DRIVING'
               };
               break;

            case "dropoff":
               var request = {
                  origin: driverPos,
                  destination: userPos,
                  travelMode: 'DRIVING'
               };
               break;
         }

         directionsService.route(request, function(result, status) {
            if (status == 'OK') {

               mydirectionsRenderer.setDirections(result);
            } else {
               console.log("error occured when calcing route  ", status);
            }
         });
      }

      function updateDriverStatus(e){
         let nextStep = e.target.getAttribute("id"),
            status = "";
         //console.log("btn", btn.getAttribute("id"));
         if(nextStep == "status2"){
            status = "washing";
         } else if(nextStep == "status3"){
            status = "dropoff";
         } else if(nextStep == "finished"){
            //end trip, add to history
            status = "finished";
            finishTrip("finished");
            return;
         } else if(nextStep == "cancelTrip"){
            //end trip, add to history, note that it was cancelled by the driver
            status = "cancelled";
            finishTrip("cancelled");
            return;
         }
         let data = new FormData;
         data.append("status", status);
         fetch("updateActiveTrip.php", {
            body: data,
            method: 'post'
         }).then(res => res.text()).then(res => {

            if(res == "SUCCESS"){
               getActiveTripDriver();
            } else {
               console.log("error occured when updating driver status ", res);
            }
         })
      }

      function finishTrip(status){
         let data = new FormData;
         data.append("status", status);

         fetch("addTripToHistory.php", {
            body: data,
            method: 'post'
         }).then(res => res.text()).then(res => {
            //reset driver page view
            document.querySelector(".driverbuttons").innerHTML = `<button id="startshift" class="btn btn-dark">Toggle Active</button>`;
            document.getElementById("driverdiv").style.display = "none";
           
            if(status == "cancelled"){
               document.getElementById("status").innerHTML = "Successfully cancelled trip!";
            } else {
               document.getElementById("status").innerHTML = "Successfully dropped off laundry!";
            }
            document.getElementById("startshift").addEventListener("click", findClient);
            initMap();
            getUnratedTrip();
         })
         
      }

      function displayUnratedTrip(trip){
         //create html element and insert it into the page as a popup
         html = `
               <h4>How was your trip with ${trip.otherName}?</h4>
               <div>
                  <div>
                     <p>${trip.otherName}</p>
                     <img src="../${trip.otherProfile}"></img>
                  </div>
                  <div id="starDiv">
                  `;
         html +=  createStars(0, "star");

         html += `        
                  </div>
                  <div>
                     <input type="text" placeholder="Additional comments"></input>
                     <button id="rateSubmit" data-user="${trip.user_id}" data-provider="${trip.provider_id}" data-transaction="${trip.transaction_id}" >Submit</button>
                  </div>
               </div>
            `;
         document.querySelector(".ratePopup").innerHTML = html;
         document.querySelector(".ratePopup").style.visibility = "visible";
         document.getElementById("rateSubmit").addEventListener("click", submitRate);
         //add click listeners to stars
         document.querySelectorAll(".fa-star").forEach(item => {item.addEventListener("click", updateStars)});
          
         
      }



      function getUnratedTrip(){
         let data = new FormData;
         //call php to check if any trip in the history of the logged in user has a rating of 0 (or whatever)
         fetch("getUnratedTrip.php", {
            method: 'post',
            body: data
         }).then(res => res.json()).then(res => {
            if(res == "NONE"){
               //do nothing; no unrated trip found
            } else {
               displayUnratedTrip(res);
            }
         })
      }

      function submitRate(e){
         //hide popup
         document.querySelector(".ratePopup").style.visibility = "hidden";
         //get rating from popup stars
         let starCount = 0;
         document.querySelectorAll(".fa-star").forEach(item => {if(item.classList.contains("checked")) {
            starCount++;
         }});
         //submit php request to add rating to the history and the user/driver
         let data = new FormData;
         data.append("rating", starCount);
         data.append("userId", e.target.getAttribute("data-user"));
         data.append("driverId", e.target.getAttribute("data-provider"));
         data.append("transactionId", e.target.getAttribute("data-transaction"));
         fetch("submitRating.php", {
            method: 'post',
            body: data
         }).then(res => res.text()).then(res => {
            if(res == "SUCCESS"){

            } else {
               console.log("Error occured when submitting rating ", res);
            }
         });
      }

      function geocode(request, name) {
         //clear();
         geocoder
            .geocode(request)
            .then((result) => {
               const { results } = result;
               //create advanced markers with results
               //map.setCenter(results[0].geometry.location);
               displayAdvancedMarker(results[0].geometry.location, name);
               map.setZoom(11);
               //add name location pair to global storage
               json = {"name": name,
                  "location": results[0].geometry.location}
               laundromats.push(json);
               return results;
            })
            .catch((e) => {
               alert("Geocode was not successful for the following reason: " + e);
            });
      }

/*---------------------------------------------------HELPERS-----------------------------------------------------*/
      function getInstructions(){
         //create JSON object from instructions form
         let iform = document.getElementById("instructions");
         let instructions = {
            "loads": document.getElementById("loadNumber").value,
            "washcycle": document.getElementById("washCycle").value,
            "washtemp": document.getElementById("washTemperature").value,
            "drytime": document.getElementById("dryTime").value,
            "drytemp": document.getElementById("dryTemperature").value,
            "additional": document.getElementById("additional").value 
         }
         return instructions;
      }

      function getLaundromatLocationFromName(pname){
         for(let i=0;i<5;i++){
            if(laundromats[i].name == pname){
               return laundromats[i].location;
            }
         }
      }

      function createListener(marker){
         infoWindow = new google.maps.InfoWindow();
         marker.addListener("click", () => {
            //const { target } = domEvent;
            createInfoWindow(marker);

         });
      }

      function createInfoWindow(marker){
         infoWindow.close();
         //insert html inside info window
         html = `
            <p>${marker.title}</p>
            <button data-title="${marker.title}" id="finalizeLaundromat">Select</button>
         `
         infoWindow.setContent(html);
         //add event listeners
         google.maps.event.addListener(infoWindow, 'domready', function(){
            document.getElementById("finalizeLaundromat").addEventListener("click", finalizeLaundromat);
         });
         infoWindow.open(marker.map, marker);
      }

      function createStars(rating, type) {
         let html = ''
         if(type === 'star') {
            for(let i=1;i<6;i++) {
               if(i === 1){
                  html += `<span class='fa fa-${type} checked' id='star1' data-order=${i}></span>`
                  
               } else {
                  if(i <= rating){
                     html += `<span class='fa fa-${type} checked' data-order=${i}></span>`
                  } else if( 0 < (i - rating) && (i - rating) < 1) {
                     html += `<span class='fa fa-star-half-o checked' data-order=${i}></span>`
                  } else {
                     html += `<span class='fa fa-star' data-order=${i}></span>`
                  }
               }
               
            }
         } else {
            html += `<p>`;
            for(let i=0;i<parseInt(rating);i++) {
               html += `$`;
            }
            html += `</p>`;
         }
         return html;
      }

      function updateStars(e){
         //make all stars before and including the one clicked checked
         let order = e.target.getAttribute("data-order");
         //check all stars including and before this one
         document.querySelectorAll("[data-order]").forEach(star => {
            if(star.getAttribute("data-order") <= order){
               star.classList.add("checked");
            } else {
               star.classList.remove("checked");
            }
         })
         
      }
</script>