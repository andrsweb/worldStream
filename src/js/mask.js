import Inputmask from "inputmask";

document.addEventListener('DOMContentLoaded', () => {
	'use strict'

	maskForInput('+7(999)999-99-99', 'top__tel')
	maskForInput('+7(999)999-99-99', 'tel')
})

const maskForInput = (mask, id) => {
	const selector = document.getElementById(id)

	if( ! selector ) return

	Inputmask({ 'mask': mask, 'placeholder': '+7(___)-___-__-__', }).mask(selector)
}