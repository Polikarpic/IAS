function counter(el)
{
var wrapper = document.createElement('DIV');
wrapper.innerHTML = el.value;
var len = (wrapper.textContent || wrapper.innerText).length;
document.getElementById('count').innerHTML = 1024 - len;
}