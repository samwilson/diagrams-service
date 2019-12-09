document.querySelectorAll('button.example').forEach(function (button) {
	button.addEventListener('click', (event) => {
		event.preventDefault();
		generatorSelect = document.getElementById('generator');
		generatorSelect.value = event.target.dataset.generator;
		generatorSelect.dispatchEvent(new Event('change'));
		document.getElementById('source').textContent = event.target.dataset.source;
	});
});

const hideElements = (selector) => {
	document.querySelectorAll(selector).forEach((el) => {
		el.style.display = 'none';
	});
};

hideElements('.gen-types:not(.gen-types-graphviz)');
const genSelect = document.getElementById('generator');
genSelect.addEventListener('change', (event) => {
	hideElements('.gen-types');
	const genGroup = event.target.querySelector('option[value="' + event.target.value + '"]').parentElement.dataset.genGroup;
	document.querySelector('.gen-types-' + genGroup).style.display = 'inline';
});
