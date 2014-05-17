function filter_func() {
	  switch($('#filter_nw').val()) {
        case "1": $('#new_works').load('./../php/work_filter/new_work_filter.php' + window.location.search); break; 
		case "2": $('#new_works').load('./../php/work_filter/new_work_all.php' + window.location.search); break;
	  }
	  switch($('#filter_ngw').val()) {
        case "1": $('#new_graduate_works').load('./../php/work_filter/new_graduate_work_filter.php' + window.location.search); break; 
		case "2": $('#new_graduate_works').load('./../php/work_filter/new_graduate_work_all.php' + window.location.search); break;
	  }
	  switch($('#filter_w').val()) {
        case "1": $('#works').load('./../php/work_filter/work_filter.php' + window.location.search); break; 
		case "2": $('#works').load('./../php/work_filter/work_all.php' + window.location.search); break;
	  }
	  switch($('#filter_bw').val()) {
        case "1": $('#b_works').load('./../php/work_filter/b_work_all.php' + window.location.search); break; 
		case "2": $('#b_works').load('./../php/work_filter/b_work_archive.php' + window.location.search); break;
	  }
	  switch($('#filter_gbw').val()) {
        case "1": $('#bg_works').load('./../php/work_filter/b_graduate_work_all.php' + window.location.search); break; 
		case "2": $('#bg_works').load('./../php/work_filter/b_graduate_work_archive.php' + window.location.search); break;
	  }
	  switch($('#filter_msg').val()) {
        case "1": $('#messages').load('./../php/work_filter/incoming_messages.php' + window.location.search); break; 
		case "2": $('#messages').load('./../php/work_filter/outgoing_messages.php' + window.location.search); break;
	  }
	  switch($('#filter_al').val()) {
        case "1": $('#alert').load('./../php/work_filter/alert.php' + window.location.search); break; 
		case "2": $('#alert').load('./../php/work_filter/applications_filed.php' + window.location.search); break;
	  }
	  switch($('#filter_user').val()) {
        default: $('#edit_user').load('./../php/work_filter/edit_user.php?z=' + $('#filter_user').val()); break; 		
	  }
}




