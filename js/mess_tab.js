var gettabcontent = function(url,mod,th) {
	var tag;
	var els=$$s.getelbyclass('tab_modules','ul');
	for(var i=0;i<els.length;i++) {
		tag=$$s.getelbytag('span',els[i]);
		for(var j=0;j<tag.length;j++) {
			$$(tag[j],'borderBottom','1px solid #555555').$$('backgroundColor','#dddddd');
		}
	}
	$$(th,'borderBottom','1px solid #ffffff').$$('backgroundColor','#ffffff');
	$$('tab_content','<img id="loading_img" src="http://scriptjava.net/img/loading.gif" />');
	$$('loading_img').src='http://scriptjava.net/img/loading.gif';
	$$a({
		url:url,
		data:{'mod':mod},
		success:function (data) {
			$$('tab_content',data);
		}
	});
}