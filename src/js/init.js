import deleteGroup from "./deleteGroup";
import {renderDots} from "./renderDots";

const init = (function () {

	window.addEventListener('load', function () {
		MicroModal.show('modal-1');

		let modalInit = document.querySelector('.modal__btn');
		modalInit.addEventListener('click', function () {

			let seatsCap = parseInt(document.querySelector('#restaurant-seats').value);

			fetch(window.location + './php/controller.php', {
				method: 'POST',
				headers: {"Content-Type": "application/json"},
				body: JSON.stringify({method: 'initSeats', data: seatsCap}),
			})
				.then((response) => response.json())
				.then((data) => {
					console.group('Init Data')
					console.dir(data);
					console.groupEnd();


					renderDots(data);

				})
				.catch((error) => {
					console.error('Error:', error);
				});
		});
	});


	return null;
})();

export default init;