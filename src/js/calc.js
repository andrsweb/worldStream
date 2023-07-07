document.addEventListener( 'DOMContentLoaded', () => {
	'use strict'

	rangeSlider()
} )

const rangeSlider = () => {
	const placesInput = document.querySelector( '.places' )
	const monthsInput = document.querySelector( '.months' )
	const monthsValue = document.querySelector( '.monthes' )
	const placesValue = document.querySelector( '.places-value' )
	const sum         = document.querySelector( '.summary')
	let num = 700000
	sum.textContent = num

	if( ! placesInput && ! monthsInput && ! monthsValue && ! placesValue && ! sum ) return


	placesInput.addEventListener('input', e => {
		placesInput.value = e.target.value
		placesValue.textContent = placesInput.value
		calc()
	})

	monthsInput.addEventListener('input', e => {
		monthsInput.value = e.target.value
		monthsValue.textContent = monthsInput.value
		calc()
	} )

	const calc = () => {
		sum.textContent = Number(placesInput.value) + Number(monthsInput.value)
	}
}



