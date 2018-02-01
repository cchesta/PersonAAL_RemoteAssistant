var stompSessionId = "";
function WS_Connect() {
    console.log("Init connection");
    var socket = new SockJS('https://giove.isti.cnr.it:8443/NewAdaptationEngine/notifyEvent');
    stompClient = Stomp.over(socket);
    stompClient.connect({}, function (frame) {
        stompSessionId = stompClient.ws.idSession;
        //$("#webSocketConnectionStatus").html("State: connected; Session id: " + stompSessionId);
        console.log("State: connected; Session id: " + stompSessionId);
        stompClient.subscribe("/user/queue/notifyEvent-request", function (msg) {
            applyRule(msg.body);
        });
		subscribeToAdaptationEngine();
    });
}

$(document).ready(WS_Connect);