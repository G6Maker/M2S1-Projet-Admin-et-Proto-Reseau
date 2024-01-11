function updateAccess(proj, user){
	var values = "projet_id="+proj+"&user_id="+user;
	fetch("/projetWeb/inc/updateAccess.php", {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
			'Content-Length': values.length
		},
		body: values
	}).then(function (response) {
		// The API call was successful!
		return response.text();
	}).then(function (html) {
		// This is the HTML from our response as a text string
		document.getElementById(proj+"u"+user).className = "dropdown-item " + html;
	}).catch(function(error) {      
		//catch
		console.log('Request failed', error);
	})
}

function addNewGroup(id){
	var proj = document.getElementById(id);
	var colbut = document.getElementById("colbut"+id);
	proj.removeChild(colbut);
	//ajax pour demander un nouveau groupe editable.
	var url = "/projetWeb/inc/newGroup.php";
	fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'Content-Length': ("id="+id).length
                },
                body: ("id="+id)
            }).then(function (response) {
                // The API call was successful!
                return response.text();
            }).then(function (html) {
                // This is the HTML from our response as a text string
                proj.innerHTML = proj.innerHTML + html;
            }).catch(function(error) {              // catch
                console.log('Request failed', error);
            })
}