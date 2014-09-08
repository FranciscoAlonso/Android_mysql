//TODO: You have to notice if the https is enabled. Depending on it will work with http or https.

function getXML()
{
    /*This variables you can modify*/
    var user     = "alice";
    var password = "alice123";
    var ipServer = "192.168.2.44";
    /*******************************/
    
    var ws = new jQuery.SOAPClient({
                    url : "https://"+ipServer+"/modules/address_book/scenarios/soap.php",
                    methode : "listAddressBook",dataType: 'jsonp',
                    async : true,
                    username : user,
                    password : password,
                    error : function()
                    {
                         document.getElementById("textArea").innerHTML= "Server connection issues";
                    },
                    data : {
                        addressBookType: "external"
                    },
                    success : function (retour, xmlhttp, xmlString)
                    {
                        document.getElementById("textArea").innerHTML= xmlString;
                    }
            });
    ws.exec();
}
