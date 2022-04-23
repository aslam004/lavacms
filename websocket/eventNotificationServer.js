var WebSocketServer = require("websocket").server;
var http = require("http");
var htmlEntity=require("html-entities");
const { client } = require("websocket");
var PORT=3280;

//list of currently connected clients(user)
var clients=[];

//create http server
var server=http.createServer();

server.listen(PORT,function(){
    console.log("Server is listening on PORT:"+PORT);
});

//create the web socket server here
server=new WebSocketServer({
    httpServer:server
});

server.on("request",function(request){
    var connection=request.accept(null,request.origin);

    //pass each connection to each client
    var index =clients.push(connection)-1;
    console.log('client',index,"connected");

    //send message to all client connected
    connection.on("message",function(message){
        var utf8Data=JSON.parse(message.utf8Data);
        
        if(message.type==='utf8'){

            //prepare the json data to be sent to all clients 
            var obj=JSON.stringify({
                eventName: htmlEntity.encode(utf8Data.eventName),
                eventMessage: htmlEntity.encode(utf8Data.eventMessage)
            });

            //send them to all clients
            for (let i = 0; i < clients.length; i++) {
                clients[i].sendUTF(obj);
            }
        }

    });
    //
    connection.on("close", function(connection) {
        clients.splice(index, 1);
        console.log("Client", index, "was disconnected");
    });
})