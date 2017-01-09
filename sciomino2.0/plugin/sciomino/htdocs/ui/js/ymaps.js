/* Yahoo maps function
 * @function
 * @name sc_Ymap
 * @description
 *      returns a function to create a Yahoo map filled with curstom markers from a search query
 * @returns function sc_Ymap.createResultMap 
 * @param {object} [jsonobj]
 *  @example
 ,      sc_YMap.createResultMap(jsonObj);
 */

var sc_YMap = (function () {
    
    var AppId = 'Jb5jhCTV34HeskpIP0509yXl_TLR8AJd88S1VAtaDvVtV_6oiVCUTZFi4DMVlBT3jvXERN0-',
        yahooScriptUrl = 'http://api.maps.yahoo.com/ajaxymap?v=3.8&appid=' + AppId,
        AppContainerId = 'YMap-container',
        pinImgSrc = '../gfx/pin-map.png',
        pinImgWidth = '26',
        pinImgHeight = '32',
        pinHtmlTemplate = '<div class="pintxt">#1</div>',
        startLoc = 'Utrecht',
        urlStart, // set from json object at start
        startZoomLevel = 11; // location and zoom

    /* @function
     * @description creates and returns custom marker img, with a img src and size
     */
    function createMarkerImage() {
        var markerImg = new YImage();
        markerImg.src = pinImgSrc;
        markerImg.size = new YSize(pinImgWidth, pinImgHeight);

        // offset relative to left,top
        markerImg.offset = new YCoordPoint(-pinImgWidth / 2, 0);

        // manage offset of smartwindow (mouseover) relative to markerImg
        markerImg.offsetSmartWindow = new YCoordPoint(pinImgWidth / 2, pinImgHeight - 3);

        return markerImg;
    }

    /* @function
     * @param {object} [locData] object describing one location 
     *      @param {object} [locData.geoLoc] object with Lon and Lat properties
     *      @param {number} [locData.nrPeople] number of hits in this location
     *      @param {string} [locData.locationName] name of the location
     * @description creates and returns marker pins
     */
    function createPin(locData) {

        var pinImg = createMarkerImage(),
            loc = locData,
            point, 
            pin,
            pinHtml;

        if (locData && locData.geoLoc) {
            point = new YGeoPoint(locData.geoLoc.Lat, locData.geoLoc.Lon);
        }

        pin = new YMarker(point, pinImg);

        pinHtml = pinHtmlTemplate.replace(/#1/, loc.nrPeople);

        pin.addLabel(pinHtml);

        // create click event on marker, to update results with chosen location
        YEvent.Capture(pin, 'MouseClick', function () {
            var newUrl = urlStart && locData.location ? urlStart + locData.location : document.location;
            document.location = newUrl;
        });

        pin.setSmartWindowColor('maroon');
        pin.addAutoExpand(locData.locationName);

        return pin;

    }

    /* @function
     * @description creates markers on map from json object, returns scope object
     *
     * @example popuLateGeoPoints.call(map, jsonArr);
     */
    function popuLateGeoPoints(jsonArr) {

        var key,
            pin,
            arr = jsonArr || [],
            arrLen = arr.length;

        while (arrLen--) {
            // create pin
            pin = createPin(arr[arrLen]);
            // add to map
            this.addOverlay(pin);
        }

        return this;
    }


    /* @function
     * @description function to create Yahoo map with custom markers
     *
     * @param {object}  [jsonObj] results is an object of n location objects
     * @param {array}  [jsonObj.hometown/workplace] two possible objects for home and work
     * @param {object} [jsonObj.hometown/workplace.locations[n]] one location object
     *      @param {string} [jsonArr[n].locationName] name of location
     *      @param {number} [jsonArr[n].nrPeople] number of hits/people on this location
     *      @param {object} [jsonArr[n].geoLoc] object with 2 properties
     *          @param {float} [jsonArr[n].geoLoc.Lat] latitude
     *          @param {float} [jsonArr[n].geoLoc.Lon] longitude
     *
     * @param {string} [jsonObj.hometown/workplace.urlStart] url for building url
     * @example 
     *      sc_YMap.createResultMap(jsonArr);
     */
    function createResultMap(jsonObj) {

        var container = document.getElementById(AppContainerId),
            map = new YMap(container),
            locationType,
            locationObj,
            markersArr;
        
        // find out which type of locations is needed (home/work)
        // defaults to hometown
        locationType = container.getAttribute('data-locationtype') || 'hometown'; 
        
        locationObj = jsonObj[locationType];

        // locationObj required to go on
        if (locationObj) {
            markersArr = locationObj.locations || [];

            // store in closure scope, fallback is current document.location
            urlStart = locationObj.urlStart ? locationObj.urlStart : document.location;

            map.setMapType(YAHOO_MAP_REG);

            map.addZoomLong(); // zoom buttons

            map.drawZoomAndCenter(startLoc, startZoomLevel);

            popuLateGeoPoints.call(map, markersArr);
        } else {
            map.setMapType(YAHOO_MAP_REG);
            map.addZoomLong();
            map.drawZoomAndCenter(startLoc, startZoomLevel);
        }


    }


    return {
        createResultMap : createResultMap
    };


}());

