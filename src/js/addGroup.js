import {renderDots} from "./renderDots";

const addGroup = (function () {

	window.addEventListener('load', function () {

		const sendBtn = document.querySelector('#sushi-reservation__add');
		sendBtn.addEventListener('click', function () {


			let data = {
				groupSize: parseInt(document.querySelector('#group-size').value)
			}

			if(data.groupSize < 2){
				document.querySelector('#modal-2-title').textContent = 'Falsche Anzahl';
				document.querySelector('#modal-2-content').textContent = 'Die kleinste erlaube Gruppengröße ist 2';
				MicroModal.show('modal-2');
				return
			}


			fetch(window.location + './php/controller.php', {
				method: 'POST',
				headers: {"Content-Type": "application/json"},
				body: JSON.stringify({method: 'addGroup', data: data}),
			})
				.then((response) => response.json())
				.then((data) => {
					console.group('Group Meta')
					console.dir(data);
					console.groupEnd();

					if(data.fail){
						document.querySelector('#modal-2-title').textContent = 'Sitzplätze voll';
						document.querySelector('#modal-2-content').textContent = 'Für die angegebene Gruppenanzahl konnte keine freie Lücke gefunden werden';
						MicroModal.show('modal-2');
						return
					}
					renderDots(data);
				})
				.catch((error) => {
					console.error('Error:', error);
				});




		});

		// just here for easier debugging
/*		const update = document.querySelector('#sushi-reservation__update');
		update.addEventListener('click',function(){
			fetch(window.location + './php/controller.php', {
				method: 'POST',
				headers: {"Content-Type": "application/json"},
				body: JSON.stringify({method: 'update'}),
			})
				.then((response) => response.json())
				.then((data) => {
					console.group('Group Meta')
					console.dir(data);
					console.groupEnd();
					renderDots(data);
				})
				.catch((error) => {
					console.error('Error:', error);
				});
		});*/
	});

	return null;
}())

export {addGroup};