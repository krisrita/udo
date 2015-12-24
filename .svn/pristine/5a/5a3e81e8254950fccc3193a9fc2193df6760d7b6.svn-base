//计时器
    timerCounter =function(){
        this.start=function(){
        var odate = new Date();
        var time = odate.getTime();
        var MinMilli = 1000 * 60;
        var HrMilli = MinMilli * 60;
        var DyMilli = HrMilli * 24;
        this.timer=setInterval(function(){
             date1 = new Date();
             time2 = date1.getTime();
             secd = Math.floor((time2-time)/1000%60)<10?'0'+Math.floor((time2-time)/1000%60):Math.floor((time2-time)/1000%60);
             minis = Math.floor((time2-time)/MinMilli%60)<10 ? '0'+Math.floor((time2-time)/MinMilli%60):Math.floor((time2-time)/MinMilli%60) ;
             hors = Math.floor((time2-time)/HrMilli%24)<10?'0'+Math.floor((time2-time)/HrMilli%24):Math.floor((time2-time)/HrMilli%24);
             dt = Math.floor((time2-time)/DyMilli);
        $('#timer').html(hors+" : "+minis+" : "+secd)
        .attr('used',parseInt((time2-time)/1000));
        },1000)

        };
        this.stop=function(){

          window.clearInterval(this.timer);

        }

    }
  
 var mytimer=new timerCounter();

     // $(window).click(function(){
     //  mytimer.stop()
     // })

// //取题
//  $.getJSON('/section/practise',{
//   section_id :pageInfo.section_id ,
//   seq:2,
//   platform:'ios'},function(json){

//     var practisedata=json.data;
//      var pText = doT.template($("#practiseTpl").text());
//      $("#pcontent").html(pText(practisedata));
//  })

//答题对象
// function Q(opt) {
//   this.getURL=opt.getURL;
//   this.postURL.opt.postURL;
//   this.randerQtml();
// } 

//转场效果
var eff={
	rollIn:'rollIn',
	rollOut:'rollOut'
}
var timerstart=false;
var Q={
  opt:{
    getURL:'/section/practise',
    seq:1,
    total:''

  },
  getQ:function(number){

  	var currentDom=$('.qlist').filter('[index='+number+']');

 // $('.qlist:visible').hide();
  $('.'+eff.rollIn).addClass(eff.rollOut).removeClass(eff.rollIn);
  	    if(currentDom.size()>0){
       	currentDom.removeClass(eff.rollOut+' '+eff.rollIn).addClass(eff.rollIn);
       	Q.setNavgation(number);
      $('#Cnum').text(number);



          }else{

          	$('.loading').fadeIn();
				$.getJSON(this.opt.getURL, {
						section_id: pageInfo.section_id,
						seq: number
					}, function(json) {

						Q.opt.total = json.data.subject_num;
						Q.randerQhtml(json.data,number);
						$('.loading').hide();

            if(!timerstart){
             mytimer.start();
             timerstart=true
            }

					})

          }

	
  },
  randerQhtml:function(data,number){

       //答题内容   
		var pText = doT.template($("#practiseTpl").text());
       $("#pcontent").append($(pText(data)).addClass(eff.rollIn));
       
       //创建右侧答题卡导航

        if($('.navitem').size()<=0){

      		for (var i =1  ; i <= data.subject_num; i++) {

      		$('<li class="navitem" index="'+i+'" id="nav'+i+'">'+i+'</li>').appendTo($('#navgation'))
      		};

		   }


	    Q.setNavgation(number)

       //第几题/共几题

       if(typeof data.next_seq === 'undefined'){
       	       $('#Cnum').text(data.subject_num);
       	   }else{
       	   	       $('#Cnum').text(data.next_seq-1);
       	   }

       $('#Anum').text(data.subject_num);


    

  },

  bindNavgation:function(){

	$('#navgation').undelegate('li');
	$('#navgation').delegate('li','click',function(){

	var cNumber=parseInt($(this).attr('index'));
	Q.getQ(cNumber);

	})


	},
 
  setNavgation:function(number){
  	$('.navitem').removeClass('on');
		$('.navitem').filter('[index='+number+']').addClass('on')

  }
  
  }

  Q.getQ(1);
  Q.bindNavgation();

  //
  function noDoneNumber(){
    var hasdoneArr=[];

    $('.qlist').each(function(key,val){
    var checkedval=$(val).find('input:checked').val();
    if(typeof checkedval != 'undefined'){
      hasdoneArr.push('yes');
    }
   });
 
   var Numbers=parseInt($('#Anum').text())-parseInt(hasdoneArr.length);



   return Numbers

  }



  $('#pcontent').delegate('input','click',function(){
       var answer=($(this).attr('subject_id')+'_'+$(this).attr('id'));
       var _this=this;

       //当前第几题 
       if($(_this).attr('next') !='undefined'){
               var currQ=$(_this).attr('next')-1;

             }else{
              currQ=$('#Anum').text()
             }

       //添加hasdone标示

      $('.navitem').filter('[index='+currQ+']').addClass('hasdone');


       window.setTimeout(function(){

          var noDones = noDoneNumber();
      if (noDones== 0) {
      bootbox.confirm('全部题目已作答，是否确认交卷?', function(result) {

        if (!result) {
          return
        } else {
          postAns()
        }
        
      })
      return;
    }


      if($(_this).attr('next')==='undefined'){

              // var noDones=noDoneNumber();
              // if(noDones>0){
              //     bootbox.warning('您有'+noDones+'道题目尚未作答哦')

              // }
         
        return; 
       }else{

       	   $('.rollIn').addClass('rollOut').removeClass('rollIn');
          Q.getQ($(_this).attr('next')); 
         

       }

       },200);




     

  })

  //提交答案

$('#sendbtn').on('click',function(){






  var noDones = noDoneNumber();
  if (noDones > 0) {
    bootbox.confirm('您有 <b>' + noDones + '</b> 道题目尚未作答,是否确认提交？', function(result) {

      if (!result) {
        return
      } else {
        postAns()
      }

    })
  } else {

    postAns()
  }






  })



  //提交答案到服务器
function postAns() {

var answerArr=[];
$('.qlist').each(function(key,val){
var subject_id=$(this).attr('subject_id');
var checkedval=$(val).find('input:checked').val() || 0;
answerArr.push (subject_id+'_'+checkedval);
console.log(answerArr.join(','));
})


    mytimer.stop();
    var answerJosn = {
        section_id: pageInfo.section_id,
        answer: answerArr.join(','),
        spend_time: $('#timer').attr('used')
      }
      //提交答案
    $.post('/section/answer', answerJosn, function(data) {

      if (data.errno >= 0) {
        window.location.href = '/practise/report/section_id/' + pageInfo.section_id

      } else {
        alert('提交失败：' + data.error);
      }

    })

  }