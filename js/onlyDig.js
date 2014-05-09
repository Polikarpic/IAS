function dig(input) { 
    input.value = input.value.replace(/[^1-9]/g,'1');
	if (input.value == '') input.value = 1;
};

function digC(input) { 
    input.value = input.value.replace(/[^1-6]/g,'1');
	if (input.value == '') input.value = 1;
};
