document.addEventListener('DOMContentLoaded', () => {
	'use strict'

	rangeSlider()
})

const rangeSlider = () => {
	const placesInput = document.querySelector('.places')
	const monthsInput = document.querySelector('.months')
	const monthsValue = document.querySelector('.monthes')
	const placesValue = document.querySelector('.places-value')
	const sum = document.querySelector('.summary')
	let num = 1400000

	sum.textContent = num

	if (!placesInput && !monthsInput && !monthsValue && !placesValue && !sum) return


	placesInput.addEventListener('input', e => {
		placesValue.textContent = e.target.value
		calc()
	})

	monthsInput.addEventListener('input', e => {
		monthsValue.textContent = e.target.value
		calc()
	})

	const calc = () => {
		const placeNum = 700000
		sum.textContent = Number((placesInput.value * placeNum) * (monthsInput.value))
	}
}



