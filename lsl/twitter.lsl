//
// Twitter for OpenSim (not an app) v0.1.3
// Developed by devi S.A.S - http://devi.com.co
// Source code: http://github.com/deviSAS/twitter-opensim
// Commercial use not allowed. Share & Remix
//
// This is a basic demo of the functionality of Twitter, you can extend it
// by modifying the code in twitter.php. Please always keep the credits and
// remember you are not allowed to use the application on a commercial basis
// even if you have modified the code.


integer listenChannel = 12;
integer listener      = -1;
string allowUrl = "You need to connect your twitter account in order to tweet from this app, select 'Goto Page' and allow access from twitter.";

list messages = ["Something went wrong, no url. ", "Status updated!", "An error ocured: ", "Logged in as @"];

string SERVER = "sp2566a"; //Our OpenSim returns the server name instead public url, set here the name and the url to be replaced bellow
string URL    = "opensim.co:";

string SITE_URL = "http://PATH_TO_TWITTER_DIRECTORY/twitter.php"; //Main Twitter-Oauth URL
string CONSUMER_KEY = "YOUR_CONSUMER_KEY_mIyWg";
integer OAUTH_STATUS = FALSE; //TRUE when connected

key httpRequest;
list metadata = [HTTP_METHOD, "POST", HTTP_MIMETYPE, "application/x-www-form-urlencoded"];
doRequest(string query, string params) {
    httpRequest = llHTTPRequest(SITE_URL + "?" + query, metadata,
        "consumer_key=" + CONSUMER_KEY + "&this_url=" + my_url + "&" + params);
}

string temp_msg = "";
string my_url   = "";
key    url_req  = NULL_KEY;

default
{
    state_entry()
    {
        url_req = llRequestURL();
    }

    http_request(key id, string method, string body)
    {
        if (url_req == id)
        {
            url_req = NULL_KEY;
            if (method == URL_REQUEST_GRANTED) {
                
                integer index = llSubStringIndex(body, SERVER);
                integer length = llStringLength(SERVER);
                my_url = llInsertString(llDeleteSubString(body, index, index + length), index, URL);
                
                llOwnerSay(my_url);
                
                listener = llListen(listenChannel, "", llGetOwner(), "");
                llOwnerSay("Type /" + (string)listenChannel + " Status here' to update your twitter status.");
                doRequest("rq=url", ""); //Register current my_url
            }
            else if (method == URL_REQUEST_DENIED) {
                llOwnerSay(llList2String(messages, 0) + body);
            }
        }
        else
        {
            string path    = llGetHTTPHeader(id, "x-path-info");
            list path_data = llParseString2List(path, ["/"], []);
            string _type = llList2String(path_data, 0);
            if(_type == "allow") {
                if(llList2String(path_data, 1) == "true") {
                    llOwnerSay(llList2String(messages, 3) + llList2String(path_data, 2));
                    OAUTH_STATUS = TRUE;
                    if(llStringLength(temp_msg)) {
                        doRequest("rq=post", "status=" + temp_msg);
                        temp_msg = "";
                    }
                } else {
                    llOwnerSay(llList2String(messages, 2) + llDumpList2String(llParseString2List(llList2String(path_data, 2), ["+"], []), " "));
                }
            } else if(_type == "status") {
                if(llList2String(path_data, 1) == "updated") {
                    llOwnerSay(llList2String(messages, 1));
                } else {
                    llOwnerSay(llList2String(messages, 2) + llDumpList2String(llParseString2List(llList2String(path_data, 2), ["+"], []), " "));
                }
            }
            
            llHTTPResponse(id, 200, "gotcha :)");
        }
    }
    
    
    listen(integer channel, string name, key id, string message)
    {
        if(OAUTH_STATUS) {
            doRequest("rq=post", "status=" + message);
        } else {
            temp_msg = message;
            doRequest("rq=allow", "");
            llOwnerSay("Please wait...");
        }
    }

    http_response(key request_id, integer status, list metadata, string body)
    {
        if(status == 200) {
            //llOwnerSay("HTTP-Response: " + body);
            list response = llParseString2List(body, [":"], []);
            if(llList2String(response, 0) == "allow") {
                llLoadURL(llGetOwner(), allowUrl, llList2String(response, 1));
            }
        }
    }
}