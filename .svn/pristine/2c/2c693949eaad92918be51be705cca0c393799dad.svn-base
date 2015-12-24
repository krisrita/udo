//监听输入

var $inputArea=$('#js-pl-textarea');
    
    $inputArea.on('keydown',function(){

       if($.trim($(this).val()).length > 5){

        $(this).removeAttr('disabled')


       }

    })


//发表评论

// course_id 	课程id
// video_id 	视频id(课程下的评论可以不传)
// comment_id 	被回复的评论id
// content 	评论内容 

	var publishData={
		course_id:courseid,
		content:'',
		comment_id:'',
		video_id: typeof(videoid)==='undefined'?'':videoid,
	}


function publish () {

	var conval=$.trim($inputArea.val());

	console.log(conval)

	


	if(conval.length<1){
		$inputArea.attr('placeholder','请输入内容...').focus();
		 return 
		}
     

    //包含“回复”和“:”就证明是回复否则为评论 干掉publishData.comment_id
	if(conval.search("回复")==0 && conval.search(":")>0){
    	publishData.content=conval.split(':')[1]
    }else{

publishData.comment_id='';
    	publishData.content=conval;

    }


	// body...
	var $pubBtn=$('#js-pl-submit');
	$pubBtn.val('发布中...').attr('disabled','true');


	$.post('/comment/publish',publishData,function(data){


		if(data.errno=='0'){

			//bootbox.success('发布成功');
			$inputArea.val('');
	     
	        $pubBtn.val('发表评论').removeAttr('disabled');

	         var repText = doT.template($("#commentListTpl").text());
	        console.log(repText(data));
           $(".commentcon").prepend(repText(data));

		}
			else{
			bootbox.danger('发布失败，请稍后再试！');
			}



	})
}

//发评论

$('#js-pl-submit').on('click',function(){


    publish()

});

//点击回复按钮

$('.commentcon').delegate('.reply','click',function(){
	console.log(this);
	var username=$(this).closest('li').find('.u_name strong').text();
	$('#js-pl-textarea').val('回复'+username+':').focus();
	publishData.comment_id=$(this).closest('li').attr('id'); 
})



$inputArea.maxlength(
{
	      alwaysShow: true,
          threshold: 5,
          warningClass: "label label-success",
          limitReachedClass: "label label-danger",
          separator: ' / ',
          validate: true,
          placement: 'bottom-left-inside'


}
); 


//获取评论
// course_id 	课程id
// video_id 	视频id(课程下的评论可以不传)
// comment_id 	评论id
// type 	0取新1取旧 
var commentData={
	course_id:courseid,
	type:1,
	video_id: typeof(videoid)==='undefined'?'':videoid,

	comment_id:0,
}

function getCommentList() {
	$.getJSON('/comment/list', commentData, function(data) {

		if (data.errno == 0) {
			$('.loading').fadeOut();

			if(data.data.length<1){
				$('#J_getmore').text('没有更多了...').attr('disabled','true')
			}

			var arrText = doT.template($("#commentListTpl").text());
			$(".commentcon").append(arrText(data));

		} else {
			bootbox.warning('获取评论失败')
		}

	})
}

	getCommentList();


//加载更多

$('#J_getmore').on('click',function(){
  
  var lastid=$('.commentcon li:last-child').attr('id'); 
	commentData.comment_id=lastid;
	getCommentList();

	
})



//举报
//comment_id 	评论id 

function report(data) {
	$.post('/comment/report', data, function(data) {

		if (data.errno == 0) {
			bootbox.success('举报成功')
		} else {
			bootbox.warning('举报失败请稍后再试')
		}

	})
}

$('.commentcon').delegate('.report','click',function(){

	report({
		comment_id:$(this).closest('li').attr('id')
	})

})


