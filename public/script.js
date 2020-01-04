document.querySelectorAll( 'button.example' ).forEach( function ( button ) {
	button.addEventListener( 'click', function ( event ) {
		// Find out which generator group this example wants.
		var generatorSelect = document.getElementById( 'generator' ),
			genGroup = generatorSelect.querySelector( ':checked' ).parentElement.dataset.genGroup,
			checkedTypesSelector = 'span.gen-types-' + genGroup + ' input[name="types[]"]:checked';

		// Set the new generator value and trigger a change
		// (to handle switching the display of types checkboxes etc.).
		generatorSelect.value = event.target.dataset.generator;
		generatorSelect.dispatchEvent( new Event( 'change' ) );

		// Set the source field to the example source.
		document.getElementById( 'source' ).textContent = event.target.dataset.source;

		// If no types are selected, select PNG.
		if ( document.querySelectorAll( checkedTypesSelector ).length === 0 ) {
			document.getElementById( 'type-' + genGroup + '-png' ).checked = true;
		}

		event.preventDefault();
	} );
} );

/**
 * Switch between generators, hiding and showing the relevant output type checkboxes.
 */
( function () {
	var genSelect = document.getElementById( 'generator' );

	genSelect.addEventListener( 'change', function ( event ) {
		var genGroupNew = event.target.querySelector( 'option[value="' + event.target.value + '"]' ).parentElement.dataset.genGroup;

		// Disable and hide all types.
		document.querySelectorAll( '.gen-types' ).forEach( function ( el ) {
			el.style.display = 'none';
		} );
		document.querySelectorAll( '.gen-types input' ).forEach( function ( el ) {
			el.disabled = true;
		} );

		// Make current generator group's types visible and enabled.
		document.querySelector( '.gen-types-' + genGroupNew ).style.display = 'inline';
		document.querySelectorAll( '.gen-types-' + genGroupNew + ' input' ).forEach( function ( el ) {
			el.disabled = false;
		} );
	} );

	genSelect.dispatchEvent( new Event( 'change' ) );
}() );
