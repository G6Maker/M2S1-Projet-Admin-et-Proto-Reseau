<?php
function printAddButton(){
	//ajouter un groupe au projet.
	?>
    <div class="container-fluid mt-2" id="colbutproj">
			<button type="button" onclick="addNewProj()" class="btn btn-outline-dark btn-lg">Nouveau Projet</button>
			<script>
				function addNewProj(){
          			var body = document.getElementsByTagName("body")[0];
					var colbut = document.getElementById("colbutproj");
					//body.removeChild(colbut);
					//fetch pour demander une nouvelle projet editable.
					var url = "/projetWeb/inc/newProj.php";
					fetch(url, {
								method: 'POST'
								/* 
                ,
                headers: {
									'Content-Type': 'application/x-www-form-urlencoded',
									'Content-Length': ("id="+id).length
								},
								body: ("id="+id)
                */
							}).then(function (response) {
								// The API call was successful!
								return response.text();
							}).then(function (html) {
								// This is the HTML from our response as a text string
								colbut.innerHTML = html;
							}).catch(function(error) {              // catch
								console.log('Request failed', error);
							})
				}
			</script>
		</div>
	<?php
}
//check if connecté
if (User::isLog()) {
	printAddButton();
}

$db = DB::getInstance();
$proj = $db->getProjets();
foreach($proj as $pr){
	if ($pr->getPublic()){
		$pr->printProjet();
	} else if (User::isLog()){
		if(User::getLogUser()->getId() == $pr->getUser_id()){
			$pr->printProjet();
		} else {
			if(Access::haveRight(User::getLogUser(), $pr)){
				$pr->printProjet();
			}
		}
	}
}
?>