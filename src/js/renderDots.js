import deleteGroup from "./deleteGroup";

let seatMeta = [];

const renderDots = function (groups) {


	let seatContainer       = document.querySelector('.sushi-table');
	seatContainer.innerHTML = '';
	let seatCap             = 0;
	let index               = 0;
	groups.forEach(function (group, groupIndex) {

		for (let i = 0; i < group.groupSize; i++) {

			index++;
			let element = document.createElement('div');
			element.classList.add('seat');
			element.style.setProperty('--i', `${index}`);
			seatContainer.appendChild(element);
			seatCap++;
		}
	});
	//console.log(seatCap)

	if (seatCap < 4) {
		window.location.reload();
	} else if (seatCap <= 10 && seatCap > 3) {
		seatContainer.style.setProperty('--tan', '0.41');
	} else if (seatCap <= 25 && seatCap > 10) {
		seatContainer.style.setProperty('--tan', '0.21');
	} else if (seatCap > 25) {
		window.location.reload();
	}

	seatContainer.style.setProperty('--m', seatCap);


	let bubbles = document.querySelectorAll('.seat');
	let key     = 0;
	seatMeta    = [];
	groups.forEach(function (group, groupIndex) {

		for (let i = 0; i < group.groupSize; i++) {

			group.groupIndex = groupIndex;

			bubbles[key].innerHTML = group.groupIndex;
			bubbles[key].setAttribute('data-group-index', group.groupIndex);
			bubbles[key].style.background = group.groupColor;

			seatMeta.push({
				personIndex: key,
				groupIndex: groupIndex,
				groupSize: group.groupSize,
				occupied: group.occupied
			});

			key++;
		}

	});

	console.group('Rendered Seats')
	console.dir(seatMeta);
	console.groupEnd();


	deleteGroup();

}

export {renderDots, seatMeta};