function Selected(a) {
var l = a.value;
	if (l == 0)
	{
        document.getElementById("teacher").style.display='none';
		document.getElementById("student").style.display='block';
	}
	else
	{
		document.getElementById("teacher").style.display='block';
		 document.getElementById("student").style.display='none';
	}
}

function SelectedN(a) {
var l = a.value;
	if (l == 'NULL')
	{
		document.getElementById("teacher").style.display='block';
		document.getElementById("student").style.display='block';	
	}
	else if (l == 0)
	{
        document.getElementById("teacher").style.display='none';
		document.getElementById("student").style.display='block';
	}
	else
	{
		document.getElementById("teacher").style.display='block';
		 document.getElementById("student").style.display='none';
	}
}
