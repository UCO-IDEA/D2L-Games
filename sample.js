/**
 * Copyright (c) 2012 Desire2Learn Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the license at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

/******************************************************************************
 * Javascript functions for the sample HTML file                              *
 ******************************************************************************/

 
 function doAPIRequest2(host, port, scheme, req, method, data, anon, callback) {
	var output;
	
	var t = {
		host: host,
		port: port,
		scheme: scheme,
		anon: anon,
		apiRequest: req,
		apiMethod: method,
		data: data
	};
	
	console.log("Sending");
	console.log(t);
	
	$.ajax({
		//note we send the values to a php file first
		// this is due to the fact that the valence javascript class had issues at the time of creation so we send to php then curl to valence, not ideal(slow) but it works
		url: "doRequest.php",
		data: t,
		success: function(data) {
			try {
				callback(data);
			} catch(e) {
				//not a good error catch
				output = "Unexpected non-JSON response from the server: " + data;
			}
		},
		error: function(jqXHR, textStatus, errorThrown) {
			console.log(jqXHR.responseText);
		}
	});
 }
 
 //from inital sample
 function doAPIRequest() {
	$('#responseField').val("");
	document.getElementById('errorField1').hidden = true;
	document.getElementById("errorField2").innerHTML = "";
	document.getElementById("responseField").hidden = true;
	document.getElementById("responseFieldLabel").hidden = true;
	$('#responseField').val("");

	var host = $('#hostField').val();
	var port = $('#portField').val();
	var scheme = $('#schemeField').is(':checked') ? 'https' : 'http';
	var req = $('#actionField').val();
	var method = $('#GETField').is(':checked') ? "GET" :
				 $('#POSTField').is(':checked') ? "POST" :
				 $('#PUTField').is(':checked') ? "PUT" : "DELETE";
	var data = $('#dataField').val();
	var anon = $('#anonymousField').is(':checked');
	
	var t = {
		host: host,
		port: port,
		scheme: scheme,
		anon: anon,
		apiRequest: req,
		apiMethod: method,
		data: data
	};
	
	console.log(t);
	
	$.ajax({
				url: "doRequest.php",
				data: {
					host: host,
					port: port,
					scheme: scheme,
					anon: anon,
					apiRequest: req,
					apiMethod: method,
					data: data,
				},
				success: function(data) {
					var output;
					if(data == '') {
						output = 'Success!';
						return;
					} else {
						try {
							console.log(data);
							
							data = JSON.parse(data);
							
							$("#test").append(data["Nickname"]);
							
							output = JSON.stringify(data, null, 4);
						} catch(e) {
							output = "Unexpected non-JSON response from the server: " + data;
						}
					}
					$('#responseField').val(output);
					document.getElementById("responseField").hidden = false;
					document.getElementById("responseFieldLabel").hidden = false;
				},
				error: function(jqXHR, textStatus, errorThrown) {
					document.getElementById('errorField1').hidden = false;
					document.getElementById("errorField2").innerHTML = jqXHR.responseText;
				}
			});
 }
 
 