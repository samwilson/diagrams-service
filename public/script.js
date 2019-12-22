document.querySelectorAll( 'button.example' ).forEach( function ( button ) {
	button.addEventListener( 'click', function ( event ) {
		var generatorSelect = document.getElementById( 'generator' );
		generatorSelect.value = event.target.dataset.generator;
		generatorSelect.dispatchEvent( new Event( 'change' ) );
		document.getElementById( 'source' ).textContent = event.target.dataset.source;
		event.preventDefault();
	} );
} );

( function () {
	var hideElements = function ( selector ) {
			document.querySelectorAll( selector ).forEach( function ( el ) {
				el.style.display = 'none';
			} );
		},
		genSelect = document.getElementById( 'generator' );

	hideElements( '.gen-types:not(.gen-types-graphviz)' );
	genSelect.addEventListener( 'change', function ( event ) {
		var genGroup = event.target.querySelector( 'option[value="' + event.target.value + '"]' ).parentElement.dataset.genGroup;
		hideElements( '.gen-types' );
		document.querySelector( '.gen-types-' + genGroup ).style.display = 'inline';
	} );
}() );
