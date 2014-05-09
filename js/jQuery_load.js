function filter_func() {
	  switch($('#filter_nw').val()) {
        case "1": $('#new_works').load('./../php/work_filter/new_work_filter.php'); break; 
		case "2": $('#new_works').load('./../php/work_filter/new_work_all.php'); break;
	  }
	  switch($('#filter_ngw').val()) {
        case "1": $('#new_graduate_works').load('./../php/work_filter/new_graduate_work_filter.php'); break; 
		case "2": $('#new_graduate_works').load('./../php/work_filter/new_graduate_work_all.php'); break;
	  }
	  switch($('#filter_w').val()) {
        case "1": $('#works').load('./../php/work_filter/work_filter.php'); break; 
		case "2": $('#works').load('./../php/work_filter/work_all.php'); break;
	  }
}




