//функция funcTab(n) вызывается событием onclick на вкладке. N - номер вкладки, на которой кликнули
function funcTab(n) {
//получаем количество вкладок по классу. В нашем случае их - 4
   var NTab = document.getElementsByClassName('tab').length;
//запускаем цикл по количеству табов. Начинаем с 1, а не 0, чтобы удобнее было подставлять значение счетчика в айдишники
  for (var i=1; i < NTab+1; i++) {
//если значение счетчика цикла не равно номеру вкладки, на которую кликаем
    if (i != n){
//текущая вкладка может быть активна или не активна, поэтому для гарантии делаем текущую вкладку все равно не активной, пропуская вкладку, на которой кликнули 
     document.getElementById('tab'+i).className = 'tab';
//тоже самое делаем для блоков с контентом
     document.getElementById('tab_content'+i).className = 'tab_content'
     }
  }
//теперь делаем таб, на котором кликнули, активным, меняя у него класс
   document.getElementById('tab'+n).className = 'tab active';
//тоже самое для блока с контентом
   document.getElementById('tab_content'+n).className = 'tab_content active';
}



