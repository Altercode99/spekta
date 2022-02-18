<?php 
if ((strpos(strtolower($_SERVER['SCRIPT_NAME']), strtolower(basename(__FILE__)))) !== false) { // NOT FALSE if the script"s file name is found in the URL
    header('HTTP/1.0 403 Forbidden');
    die('<h2>Direct access to this page is not allowed.</h2>');
}

$script = <<< "JS"
	function showRoomList() {	 
        var selectedRoom;
        var eventView;

        var listLayout = mainTab.cells("meeting_room_list").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    text: "Ruang Meeting"
                },
                {
                    id: "b",
                    header: null
                }
            ]
        });

        var rsvLayout = listLayout.cells("b").attachLayout({
            pattern: "2E",
            cells: [{
                    id: "a",
                    header: false
                },
                {
                    id: "b",
                    text: "Detail Reservasi"
                }
            ]
        });

        var dateLayout = rsvLayout.cells("a").attachLayout({
            pattern: "2U",
            cells: [{
                    id: "a",
                    text: "Pilih Tanggal",
                    width: 285
                },
                {
                    id: "b",
                    text: "Total Reservasi"
                }
            ]
        });

        var rooms =  reqJsonResponse(RoomRev("getRoomsView"), "GET", null);

        roomView = listLayout.cells("a").attachDataView({
            container: "room_container",
            type:{
                template: rooms.template,
                height: 160
            },
            autowidth: 1,
        });

        if(rooms.data.length > 0) {
            rooms.data.map(room => roomView.add(room));
        }

        roomView.attachEvent("onAfterSelect", function(id) {
            if(rooms.data.length > 0) {
                rooms.data.map(room => id === room.id && detailRoom(id));
            }
        });

        function detailRoom(roomId) {
            rsvLayout.cells("b").setText("Detail Reservasi");
            rsvLayout.cells("b").attachHTMLString("<div></div>");
            dateLayout.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:column;justify-content:center;align-items: center;'><p style='font-size:128px;font-family:sans-serif'>0</p></div>")
            selectedRoom = roomId;
            var rsvForm = dateLayout.cells("a").attachForm([
                {
                    type: "container", 
                    name: "calendar", 
                    id: "calendar-info"
                },
            ]);

            var rsvCalendar = new dhtmlXCalendarObject(rsvForm.getContainer("calendar"));
            rsvCalendar.hideTime();
            rsvCalendar.show();
            rsvCalendar.setPosition(15, 5);

            function loadEventToCalendar() {
                let date = rsvCalendar.getDate();
                reqJson(RoomRev("getEventCalendar"), "POST", {
                    table: "meeting_rooms_reservation",
                    column: "room_id",
                    id: roomId, 
                    date: date.toISOString()
                }, (err, res) => {
                    if(!err) {
                        if(res.status === "success") {
                            if(res.dates !== "") {
                                rsvCalendar.setHolidays(res.dates);
                                loadDetailEvent(roomId, date);
                            }
                        }
                    } else {
                        eAlert("Get Event Calendar gagal!");
                    }
                });
            }

            loadEventToCalendar();
            
            rsvCalendar.attachEvent("onClick", function(date){
                rsvLayout.cells("b").setText("Detail Reservasi: " + indoDate(date));
                loadDetailEvent(roomId, date);
            });

            function loadDetailEvent(roomId, date) {
                rsvLayout.cells("b").setText("Detail Reservasi: " + indoDate(date));
                reqJson(RoomRev("getEventDate"), "POST", {roomId, date: date.toISOString()}, (err, res) => {
                    if(!err) {
                        if(res.status === "success") {
                            rsvLayout.cells("b").attachHTMLString(res.template);
                            dateLayout.cells("b").attachHTMLString("<div style='width:100%;height:100%;display:flex;flex-direction:column;justify-content:center;align-items: center;'><p style='font-size:128px;font-family:sans-serif'>"+res.total+"</p></div>")
                        }
                    } else {
                        eAlert("Get Event Calendar gagal!");
                    }
                });
            }
        }
    }

    function detailEventDate(id) {
        var eventWin = createWindow("event-detail", "Detail Meeting", 800, 500);
        myWins.window("event-detail").skipMyCloseEvent = true;
        eventWin.progressOn();
        eventGrid = eventWin.attachGrid();
        eventGrid.setHeader("Detail Meeting,#cspan");
        eventGrid.setColSorting("str,str");
        eventGrid.setColAlign("left,left");
        eventGrid.setColTypes("rotxt,rotxt");
        eventGrid.setInitWidthsP("25,75");
        eventGrid.enableSmartRendering(true);
        eventGrid.attachEvent("onXLE", function() {
            eventWin.progressOff();
        });
        eventGrid.init();
        eventGrid.clearAndLoad(RoomRev("getEventDetail", {eventId: id}));
    }

JS;

header('Content-Type: application/javascript');
echo $script;
    