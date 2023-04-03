import {seatMeta} from "./renderDots";
import {renderDots} from "./renderDots";

const deleteGroup = function () {

	let seats = document.querySelectorAll('.seat');

	seats.forEach(function (seat, index) {

		seat.addEventListener('click', function () {

			let seatIndex = Array.from(seat.parentElement.children).indexOf(seat);

			console.log(seatMeta[seatIndex]);

			if(!seatMeta[seatIndex].occupied){
				document.querySelector('#modal-2-title').textContent = 'Kann nicht gelöscht werden';
				document.querySelector('#modal-2-content').textContent = 'Das ist bereits eine Lücke und kann daher nicht gelöscht werden';
				MicroModal.show('modal-2');
				return
			}

			fetch(window.location + './php/controller.php', {
				method: 'POST',
				headers: {"Content-Type": "application/json"},
				body: JSON.stringify({method: 'deleteGroup', data: seatMeta[seatIndex]}),
			})
				.then((response) => response.json())
				.then((data) => {
					console.group('Group Array')
					console.dir(data);
					console.groupEnd();
					renderDots(data);
				})
				.catch((error) => {
					console.error('Error:', error);
				});
		});
	})

	return null;
};

export default deleteGroup;