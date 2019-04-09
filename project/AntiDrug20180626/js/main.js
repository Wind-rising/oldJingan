$(function(){
	let winH = window.innerHeight;
	let winW = window.innerWidth;
	//正在读取中的关卡文件
	let loadingBank = [];
	//读取完成的关卡文件
	let bank = [];
	//完成的管卡数据
	/*
		{
			"1"//关卡索引:data//关卡数据
		}
	*/
	let checkPointData = {};
	//当前可到达关卡
	let nowBank = 4;
	//最大关卡
	let maxBank = -1;
	//页面集合
	let pageArr = [];
	//关卡分数
	let scoreArr;
	//当前红包数
	let currentRedArr;
	//关卡对象
	let cpObj;
	//红包对象
	let redObj;
	//资源加载定时器
	let timer_res = false;
	//加载结束运行函数
	let overFn;
	//最大可获取红包
	let maxRed = -1;
	//是否领取红包
	let isGetRed = 0;
	//是否闯过第一关
	let isFirstCP = 0;

	/*
	*关卡参数
	*/ 
	//当前关卡分数
	let score = 0;
	//当前关卡答题索引
	let questionIndex = 0;
	//当前关卡定时器
	let timer;
	//当前关卡倒计时时间
	let countDown = 20;
	//当前关卡题组
	let questionData;
	//小关数据
	let questionSubData;
	//当前答题数据记录
	let answerData = [];
	//当前需要答题个数
	let answerCount = 0;
	let answerCount2 = 0;
	//答题数据
	let sendData = {};
	// 错误状态
	let errorState;
	// [
	// 	{
	// 		"questionID":1,
	// 		"selectID":[0,1]
	// 	}
	// ]
	/*
	流程
	初始化数据 - 1.预读管卡数据，
			   - 2.读取用户数据
			     - 2.1根据用户数据修改页面配置

	答题操作方式  - 点击答题管卡
			  	  - 点击答题按钮

	答题  -1.获取答题管卡
		  -2.随机10题关卡题目
		  -3.按照索引显示题目
		  -4.点击答案判断（只要点击错误答案直接错误）
		  	-4.1正确（直接跳转下一题）
		  	-4.2错误（显示正确答案），用户点击进入下一题
		    -4.3倒计时到达目标0（显示正确答案），用户点击进入下一题
		  -5.进入结算页面,返还数据给后台
	*/
    document.oncontextmenu=function(e){
        e.preventDefault();
    };
    document.addEventListener('WeixinJSBridgeReady', function() {
        $("#button")[0].play();
        $("#button")[0].pause();
        $("#win")[0].play();
        $("#win")[0].pause();
        $("#lose")[0].play();
        $("#lose")[0].pause();
    })

	//读取初始化数据
	init();
	
	$.ajax({
		"url":"http://wx.wuliqinggu.com/activityn/getInfo",
		"dataType":"json",
		"success":function(res){
			console.log(res)
			if(res.code==0){
				//根据关卡读取配置信息
				maxBankChange(res.data.max);
				scoreArr = res.data.score;
				currentRedArr = res.data.currentRed;
				maxRed = res.data.maxRed;
				isGetRed = res.data.isGetRed;
				isFirstCP = res.data.isFirstCP;
				dataForPage();
			}	
		}
	})
	// 胖子你好呀又出来了

	// $.getJSON("./testData/loginData.json",function(res){
	// 	//判断数据是否异常
	// 	if(res.code==0){
	// 		//根据关卡读取配置信息
	// 		maxBankChange(res.data.max);
	// 		scoreArr = res.data.score;
	// 		currentRedArr = res.data.currentRed;
	// 		dataForPage();
	// 	}
	// })
	/*
	*初始化结构
	*/
	function init(){
		//预读第一关关卡配置
		maxBankChange(nowBank);
		//获取关卡对象
		cpObj = getBackObj('.checkPointSub');
		//绑定点击对象
		bindClickStartGame(cpObj);
		//获取红包对象
		redObj = getBackObj('.redPackage');
		//绑定点击对象
		bindClickRed(redObj);
		//绑定开始答题按钮
		$(".startQuestion").click(startCheckPoint)
		$(".next").click(nextQuestion);
		//将页面写入page集合
		pageArr = $(".page");
		//绑定继续游戏
		$(".continueGame").click(startCheckPoint)
		//绑定终止游戏按钮
		$(".endGame").click(endGame);
		//绑定进入游戏按钮
		$(".moveGame").click(function(){
			showPage(1);
		})
		$(".moveGame2").click(function(){
			showPage(2);
		})
		$(".rule").click(function(){
			$(".popup_rule").show();
		})
		$(".popup_rule .close").click(function(){
			$(".popup_rule").hide();
		})
		$(".popup_tips .close").click(function(){
			$(".popup_tips").hide();
		})
		$(".popup_tips2 .close").click(function(){
			$(".popup_tips2").hide();
		})
	}

	let count = 0;
	let imageArr = [
        "../project/AntiDrug20180626/images/5-121204193R5-50.gif",
        "../project/AntiDrug20180626/images/bg_new_line.png",
        "../project/AntiDrug20180626/images2/main_bg.png",
        "../project/AntiDrug20180626/images2/bg2.png",
        "../project/AntiDrug20180626/images2/bg3.png",
        "../project/AntiDrug20180626/images2/button_wrap.png",
        "../project/AntiDrug20180626/images2/main_image1.png",
        "../project/AntiDrug20180626/images2/word_3.png",
        "../project/AntiDrug20180626/images2/title.png",
        "../project/AntiDrug20180626/images2/word_1.png",
        "../project/AntiDrug20180626/images2/word2.png",
        "../project/AntiDrug20180626/images2/buttom_bg.png",
        "../project/AntiDrug20180626/images2/word_4.png",
        "../project/AntiDrug20180626/images/wrap_a_line.png",
        "../project/AntiDrug20180626/images/wrap_a_left.png",
        "../project/AntiDrug20180626/images/wrap_a_right.png",
        "../project/AntiDrug20180626/images2/4_1.png",
        "../project/AntiDrug20180626/images2/4_2.png",
        "../project/AntiDrug20180626/images2/4_3.png",
        "../project/AntiDrug20180626/images2/3_1.png",
        "../project/AntiDrug20180626/images2/3_2.png",
        "../project/AntiDrug20180626/images2/3_3.png",
        "../project/AntiDrug20180626/images2/2_1.png",
        "../project/AntiDrug20180626/images2/2_2.png",
        "../project/AntiDrug20180626/images2/2_3.png",
        "../project/AntiDrug20180626/images2/1_1.png",
        "../project/AntiDrug20180626/images2/1_2.png",
        "../project/AntiDrug20180626/images2/1_3.png",
        "../project/AntiDrug20180626/images2/0_1.png",
        "../project/AntiDrug20180626/images2/0_2.png",
        "../project/AntiDrug20180626/images2/0_3.png",
        "../project/AntiDrug20180626/images2/button_2.png",
        "../project/AntiDrug20180626/images/icon_star1.png",
        "../project/AntiDrug20180626/images/icon_star2.png",
        "../project/AntiDrug20180626/images2/red_have.png",
        "../project/AntiDrug20180626/images2/red_no.png",
        "../project/AntiDrug20180626/images/icon_dui.png",
        "../project/AntiDrug20180626/images2/s_1.png",
        "../project/AntiDrug20180626/images2/s_2.png",
        "../project/AntiDrug20180626/images2/s_3.png",
        "../project/AntiDrug20180626/images2/s_4.png",
        "../project/AntiDrug20180626/images2/s_5.png",
        "../project/AntiDrug20180626/images2/f_1.png",
        "../project/AntiDrug20180626/images2/f_2.png",
        "../project/AntiDrug20180626/images2/f_3.png",
        "../project/AntiDrug20180626/images2/f_4.png",
        "../project/AntiDrug20180626/images2/f_5.png",
        "../project/AntiDrug20180626/images/icon_xiao.png",
        "../project/AntiDrug20180626/images/icon_face.png",
        "../project/AntiDrug20180626/images2/word_countieGame.png",
        "../project/AntiDrug20180626/images/word_getAward.png",
        "../project/AntiDrug20180626/images2/word_countieGame.png",
        "../project/AntiDrug20180626/images/word_restart.png",
        "../project/AntiDrug20180626/images2/word_6.png",
        "../project/AntiDrug20180626/images/icon_x.png",
        "../project/AntiDrug20180626/images2/wrong_icon.png",
        "../project/AntiDrug20180626/images2/word_next.png",
        "../project/AntiDrug20180626/images2/word_5.png",
        "../project/AntiDrug20180626/images/icon_success.png",
	];
	loadingImage();




	function loadingImage(){
		var img=new Image();
	    img.src=imageArr[count];
	    img.onload=function(){
	      count++;
	      // $(".loading").html(parseInt(count/imageArr.length*100)+"%")
	      if(count >= imageArr.length){
	      	loadingOver();
	      	return;
	      }else{
	      	loadingImage();
	      }
	    }
	}
	function loadingImage(){
		for(let i = 0 ;i<imageArr.length;i++){
			let img=new Image();
		    img.src=imageArr[i];
		    img.onload=function(){
		      count++;
		      // $(".loading").html(parseInt(count/imageArr.length*100)+"%")
		      if(count >= imageArr.length){
		      	loadingOver();
		      }
		    }
		}
	}
	function loadingOver(){
	  	$(".loading").hide();
	  	showPage(0)
	}
	
	
	
	





	/*
	*检测资源加载函数
	*/ 
	function startTest(){
		if(timer_res)return;
		timer_res = true;
		TestDataLoading();
	}
	/*
	*检测数据是否加载完成
	*/ 
	function TestDataLoading(){
		if(loadingBank.length>0){
			requestAnimationFrame(TestDataLoading);	
		}else{
			timer_res = false;
			//结束执行
			overFn&&overFn();
			overFn = undefined;
		}
	}



	/*
	*倒叙获取对象
	*/
	function getBackObj(className){
		let obj = $(className);
		let arr = [];
		for(let i = obj.length-1;i>=0;i--){
			obj[i].id = obj.length-i-1;
			arr.push(obj[i]);
		}
		return arr;
	}
	/*
	*随机函数
	*/ 
	function rnd(min,max){
		return Math.floor(Math.random() * (max - min + 1)) + min;
	}
	/*
	*页面控制方法
	*@n页面索引
	*/ 
	function showPage(n){
		pageArr.hide();
		pageArr.eq(n).show();
		if(n == 0){
            pageArr.eq(n).addClass('on');
		}
	}

	let nowButton;
	$('.button').on("touchstart",function(){
		$(this).addClass('on');
        $("#button")[0].currentTime = 0;
		$("#button")[0].play();
        nowButton = this;
	})

    $("html").on("touchend",function(){
    	if(nowButton){
    		$(nowButton).removeClass('on');
		}
    })
	/*
	*关卡配置获取函数封装
	*可答题关卡配置
	*@bank,当前改变的库值
	*/ 
	function maxBankChange(bank){
		nowBank = bank;
		if(nowBank>maxBank){
			maxBank = bank
		}else{
			return;
		}
		for(let i = 0;i<nowBank+1;i++){
			if(i>4){
				return;
			}
			getCheckPoint(i);
		}
	}
	/*
	*关卡配置获取函数封装
	*@checkPoint 关卡数
	*/
	function getCheckPoint(checkPoint){
		/*
		*查看是否有关卡重复加载
		*当重复时return
		*/
		for(let i = 0;i<loadingBank.length;i++){
			if(loadingBank[i]==checkPoint){
				return;
			}
		}
		for(let i = 0;i<bank.length;i++){
			if(bank[i]==checkPoint){
				return;
			}
		}
		//检测通过加入loading列表
		loadingBank.push(checkPoint);2
		startTest();
		let timeData = +new Date();
		//执行关卡配置加载
		$.getJSON("../project/AntiDrug20180626/json/checkPoints_"+checkPoint+".json?"+timeData,function(data){
			//成功加载
			//移除关卡的loading状态
			loadingBank.splice(loadingBank.indexOf(checkPoint),1);
			//关卡信息转为加载完成，将数据存储待加载
			bank.push(checkPoint);	
			checkPointData[checkPoint] = data;
		});
	}


	/*
	*根据数据初始
	*/ 
	function dataForPage(){
		redInit();
		checkPointInit();
	}
	/*
	*红包个数初始化
	*/
	function redInit(){
		$(redObj).hide();
		$(redObj).removeClass("on");
		for(let i = 0;i<redObj.length;i++){
			if(currentRedArr[i]<=0){
				$(redObj[i]).addClass("on");
			}
			if(currentRedArr[i]>=1000){
				$(redObj[i]).find(".redNum").addClass("on");	
			}else{
				$(redObj[i]).find(".redNum").removeClass("on");	
			}
			$(redObj[i]).find(".redNum").html(currentRedArr[i]);
			if(i<=maxRed){
				$(redObj[i]).show();
			}
		}
		if(isFirstCP==1){
			$(redObj[0]).show();
		}
	}
	/*
	*关卡初始化
	*/ 
	function checkPointInit(){
		$(cpObj).find(".this_state").removeClass("this_state1");
		$(cpObj).find(".this_state").removeClass("this_state2");
		$(cpObj).find(".this_state").removeClass("this_state3");
		for(let i = 0;i<cpObj.length;i++){
			if(i>nowBank){
				$(cpObj[i]).find(".this_state").addClass("this_state1");
			}else if(i == nowBank){
				$(cpObj[i]).find(".this_state").addClass("this_state2");
			}else{
				$(cpObj[i]).find(".this_state").addClass("this_state3");
			}
		}
	}

	//交互绑定
	/*
	*绑定进入答题点击事件
	*@obj 绑定对象
	*/ 
	function bindClickStartGame(obj){
		for(let i = 0;i<obj.length;i++){
			$(obj[i]).click(function(){
				let index = cpObj.length - 1 - $(".checkPointSub").index($(this));
				if( index == nowBank){
					getQustion(index,10);
					startOneQuestion();
				}
			})
		}
	}
	/*
	*进入答题点击事件
	*/ 
	// function onMove(){
	// 	if(this.id==nowBank){
	// 		getQustion(this.id,10);
	// 		startOneQuestion();
	// 	}
	// }

	/*
	*绑定红包事件
	*/
	function bindClickRed(obj){
		for(let i = 0;i<obj.length;i++){
			$(obj[i]).click(giveRed)
		}
	}
	/*
	*红包点击触发函数
	*/
	function giveRed(){
		// let index = $(this).index();
		let index = redObj.length - 1 - $(".redPackage").index($(this));
		if(currentRedArr[index]<=0){
			$(".popup_tips2 .popup_main").css({
				"opacity":0,
			})
			$(".popup_tips2").show();
			$(".popup_tips2 .p1").html("红包以发放完毕");
			$(".popup_tips2 .popup_main").css({
				"margin-top":-$(".popup_tips2 .popup_main").height()/2,
				"opacity":1
			})
		}else{
			if(isGetRed == 1){
				console.log(1)
				$(".popup_tips2 .popup_main").css({
					"opacity":0,
				})
				$(".popup_tips2").show();
				$(".popup_tips2 .p1").html("您今日已获取红包领取资格，点击《平安静安》公众号下方菜单栏中《领取红包》按钮，领取您的大红包吧!");
				$(".popup_tips2 .popup_main").css({
					"margin-top":-$(".popup_tips2 .popup_main").height()/2,
					"opacity":1
				})	
				return;
			}
			let url = "http://wx.wuliqinggu.com/activityn/getRedPack?checkpoint="+index;
			// let url = "http://wx.wuliqinggu.com/activity/getRedPack";
			$.ajax({
				"url":url,
				"type":"GET",
				"dataType":"json",
				// "data":{
				// 	"checkpoint":index
				// },
				"success":function(res){
					if(res.code==0){
						$(".popup_tips .popup_main").css({
							"opacity":0,
						})
						$(".popup_tips").show();
						$(".popup_tips .popup_main").css({
							"margin-top":-$(".popup_tips .popup_main").height()/2,
							"opacity":1
						})
						isGetRed = 1;
						currentRedArr = res.data.currentRed;
						dataForPage();
					}else if(res.code==1){
						$(".popup_tips2 .popup_main").css({
							"opacity":0,
						})
						$(".popup_tips2").show();
						$(".popup_tips2 .p1").html(res.data.reason);
						$(".popup_tips2 .popup_main").css({
							"margin-top":-$(".popup_tips2 .popup_main").height()/2,
							"opacity":1
						})
						if(res.data.currentRed){
							currentRedArr = res.data.currentRed;
							dataForPage();
						}
					}else if(res.code == 3){
                        location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa9da81f585c9d3b0&redirect_uri=http%3A%2F%2Fwx.wuliqinggu.com%2Factivityn%2Findex&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                    }
				}
			})
			
		}
	}

	//红包发放完毕弹窗
	function showRedPopup(){

	}
	//红包发放提示弹窗

	//答题逻辑
	/*
	*选择对应题目
	*@id题库ID
	*@num题目个数
	*/ 
	function getQustion(id,num){
		showPage(3)
		$(".page3").css({
			opacity:0
		})
		score = 0;
		questionIndex = 0;
		questionData = undefined;
		questionSubData = undefined;
		answerData = [];
		answerCount = 0;
		sendData = {};
		let cp_name = "";
		switch(id){
			case 0:
				cp_name = "青铜难度";
				break;
			case 1:
				cp_name = "白银难度";
				break;
			case 2:
				cp_name = "黄金难度";
				break;
			case 3:
				cp_name = "铂金难度";
				break;
			case 4:
				cp_name = "钻石难度";
				break;
		}
		// $(".cp_name").html(cp_name)
		$(".score").html("得分："+score+"分")
		let data = checkPointData[id].data;
		let arr=[];
		while(arr.length<num){
			let n = rnd(0,data.length-1);
			let isCF = false;
			for(let i = 0;i<arr.length;i++){
				if(arr[i].id == n){
					isCF = true;
					break;
				}
			}
			if(!isCF)arr.push(data[n]);
		}
		sendData = {};
		sendData.checkpointID = id;
		sendData.answer = [];
		answerData = arr;
	}
	/*
	*答题逻辑
	*/
	function startOneQuestion(){
		answerCount = 0;
		answerCount2 = 0;
		let data = answerData[questionIndex];
		// console.log(data)
		sendData.answer[questionIndex] = {};
		sendData.answer[questionIndex].questionID = data.id;
		sendData.answer[questionIndex].selectID = [];
		// console.log(data.id)
		$(".currentsQusetion").html(questionIndex+1);
		countDownFn();
		$(".answerSub").hide();
		let arr = [];

		// console.log(arr,2)
		while(arr.length<data.option.length){
			let n = rnd(0,data.option.length-1);
			let isCF = false;
			for(let i = 0;i<arr.length;i++){
				if(arr[i].id == data.option[n].id){
					isCF = true;
				}
			}
			if(!isCF)arr.push(data.option[n]);
		}
		// console.log(arr,1)
		questionSubData = arr;
		$(".answerSub").removeClass("on");
		for(let i = 0;i<arr.length;i++){
			if(arr[i].isCorrect){
				answerCount2++;
				answerCount++;	
			}
			$(".answerSub").eq(i).unbind("click");
			$(".answerSub").eq(i).show().find(".content").html(String.fromCharCode(65+i)+"."+arr[i].optionSub);
			$(".answerSub").eq(i).bind("click",function(){
				$(this).unbind("click");
				$(this).addClass("on");
				let index = $(".answerSub").index($(this));
				// sendData.answer[questionIndex].selectID.push(questionSubData[index].id);
				let index2 = 0;
				let arr = sendData.answer[questionIndex].selectID;
				for(let i=0;i<arr.length;i++){
					if(arr[i]>questionSubData[index].id){
						index2 = i;
						break;
					}else{
						index2 = i+1;
					}
				}
				arr.splice(index2,0,questionSubData[index].id);

				console.log(arr)
				let isCorrect = questionSubData[index].isCorrect;
				if(questionSubData[index].isCorrect == 0){
					errorState = 0;
					errorTips();
				}else if(questionSubData[index].isCorrect == 1){
					answerCount--;
					if(answerCount == 0){
						//加分
						score += 10;
						$(".score").html("得分："+score+"分");
						$(".answerSub").unbind("click");
						$(".popup_success").show();
						setTimeout(function(){
							nextQuestion();
						},1000)
					}
				}
			})
		}
		let state;
		if(arr.length == 2){
			state = "(判断题)  ";
		}else if(arr.length == 3||arr.length == 4){
			if(answerCount>1){
				state = "(多选题)  ";
			}else{
				state = "(单选题)  ";
			}
		}
		$(".question").find(".xulie").html((questionIndex+1)+".")
		$(".question").find(".state").html(state);
		$(".question").find(".content").html(data.question);
		if($(".wrap_page3").height()>winH*0.56){
			
			if($(".wrap_page3").height()>winH*0.95){
				$(".container").css({
					"overflow":"scroll"
				})	
			}
			$(".page3").css({
				// "overflow":"visible",
				// "overflow-y":"scroll",
				"height":$(".wrap_page3").height()+winH*0.44,
				"opacity":1
			})
		}else{
			$(".container").css({
				"overflow":"hidden",
			})
			$(".page3").css({
				"overflow":"hidden",
				// "overflow-y":"hidden",
				"height":"100%",
				"opacity":1
			})
		}
		$(".container").scrollTop(0);
	}
	/*
	*错误提示
	*/
	function errorTips(){
		clearInterval(timer);
		if(errorState == 0){
			$(".error_aa").html("很遗憾回答错误!");
		}else if(errorState == 1){
			$(".error_aa").html("很遗憾答题时间结束!");
		}
		$(".answerSub").unbind("click");
		let data = answerData[questionIndex];
		// $(".question2").html(data.question);
		$(".error_tips .p4").hide();
		$(".error_tips .p3").hide();
		$(".error_tips .p2").hide();	
		if(answerCount2==1){
			$(".error_tips .p2").show();
			for(let i = 0;i<questionSubData.length;i++){
				if(questionSubData[i].isCorrect == 1){
					$(".error_tips .p2").find(".xuanxiang").html(String.fromCharCode(65+i)+".");
					$(".error_tips .p2").find(".content").html(questionSubData[i].optionSub);
				}
			}
		}else if(answerCount2>1){
			$(".error_tips .p3").show();
			for(let i = 0;i<questionSubData.length;i++){
				if(questionSubData[i].isCorrect == 1){
					$(".error_tips .p4").eq(i).show().find(".xuanxiang").html(String.fromCharCode(65+i)+".");
					$(".error_tips .p4").eq(i).show().find(".content").html(questionSubData[i].optionSub);
				}
			}	
		}
		$(".error_tips").css({
			opacity:0
		});
		$(".error_tips").show();
		if($(".error_tips .popup_main").height()>winH*0.8){
			// $(".error_tips .popup_main").height(winH*0.8);

			
			$(".error_tips .popup_scroll").hide();
			let height = winH*0.8 - $(".error_tips .popup_main").height();
			$(".error_tips .popup_scroll").css({
				"display":"block",
				"height":height,
				"overflow-y":"scroll"
			});
			$(".error_tips .popup_main").css({
				"top":"50%",
				"margin-top":-$(".error_tips .popup_main").height()/2
			})	
		}else{
			$(".error_tips .popup_scroll").height("100%");
			$(".error_tips .popup_main").css({
				"top":"50%",
				"margin-top":-$(".error_tips .popup_main").height()/2
			})	
		}
		$(".error_tips").css({
			opacity:1
		});
	}

	/*
	*下一题
	*/
	function nextQuestion(){
		$(".popup_success").hide();
		clearInterval(timer);
		questionIndex++;
		$(".error_tips").hide();
		if(questionIndex>=10){
			$(".container").css({
				"overflow":"hidden",
			})
			$(".success").hide();
			$(".failed").hide();
			// let jsonA = {
			// 	"answer_list":sendData
			// }
			let url = "http://wx.wuliqinggu.com/activityn/getScore?answer_list="+JSON.stringify(sendData)
			$.ajax({
				"url":url,
				// "data":jsonA,
				// "answer_list":sendData,
				"dataType":"json",
				"type":"GET",
				"success":function(res){
					if(res.code==0){
						isFirstCP = res.data.isFirstCP;
						$(".page4_score").html(res.data.score[sendData.checkpointID]);
						if(res.data.score[sendData.checkpointID]>=90){
							let num;
							switch(sendData.checkpointID){
								case 0:
									num = "一";
									break;
								case 1:
									num = "二";
									break;
								case 2:
									num = "三";
									break;
								case 3:
									num = "四";
									break;
								case 4:
									num = "五";
									break;
							}
                            $(".success .title_word").removeClass("title_1");
                            $(".success .title_word").removeClass("title_2");
                            $(".success .title_word").removeClass("title_3");
                            $(".success .title_word").removeClass("title_4");
                            $(".success .title_word").removeClass("title_5");
                            $(".success .gif").attr('src','')
							if(num == "五"){
								$(".success").find(".page4_index").html(num);
								$(".success").find(".p3").html("恭喜你成功通关，可拥有领取最高红包机会！");
							}else{ 
								$(".success").find(".page4_index").html(num);
								$(".success").find(".p3").html("乘胜追击，继续闯关吧");
							}
							$(".success").show();
                            $(".success .gif").show();

                            $(".success .gif").attr('src','../project/AntiDrug20180626/images2/win.gif')

                            $("#win")[0].currentTime = 0;
                            $("#win")[0].play();
                            setTimeout(()=>{
                                $(".success .gif").hide();
                                $(".success .gif").attr('src','')
                                $(".success .title_word").addClass("title_"+(sendData.checkpointID+1));
                            },2500)
						}else{
                            $(".failed .title_word").removeClass("title_1");
                            $(".failed .title_word").removeClass("title_2");
                            $(".failed .title_word").removeClass("title_3");
                            $(".failed .title_word").removeClass("title_4");
                            $(".failed .title_word").removeClass("title_5");
                            $(".failed .gif").attr('src','')
							$(".failed").show();
                            $(".failed .gif").show();
                            $(".failed .gif").attr('src','../project/AntiDrug20180626/images2/lose.gif')

                            $("#lose")[0].currentTime = 0;
                            $("#lose")[0].play();
                            setTimeout(()=>{
                                $(".failed .gif").hide();
                                $(".failed .gif").attr('src','')
                                $(".failed .title_word").addClass("title_"+(sendData.checkpointID+1));
                            },2500)
						}
						maxBankChange(res.data.max);
						scoreArr = res.data.score;
						currentRedArr = res.data.currentRed;
						maxRed = res.data.maxRed;
						dataForPage();
						showPage(4);
					}else if(res.code == 3){
                        location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wxa9da81f585c9d3b0&redirect_uri=http%3A%2F%2Fwx.wuliqinggu.com%2Factivityn%2Findex&response_type=code&scope=snsapi_base&state=1#wechat_redirect"
                    }
				}
			})
			return;
		}
		startOneQuestion();
	}
	/*
	*关卡倒计时
	*/ 
	function countDownFn(){
		clearInterval(timer);
		countDown = 20;
		$(".countDown").html(countDown+"s");
		timer = setInterval(()=>{
			countDown--;
			$(".countDown").html(countDown+"s");
			if(countDown <= 0){
				clearInterval(timer);
				questionOver();		
			}
		},1000)
	}
	/*
	*小关失败
	*/ 
	function questionOver(){
		console.log("时间到")
		errorState = 1;
		errorTips();
	}

	/*
	*继续挑战函数
	*/
	function startCheckPoint(){
		if(nowBank == 5){
			$(".popup_tips2 .popup_main").css({
				"opacity":0,
			})
			$(".popup_tips2").show();
			$(".popup_tips2 .p1").html("您成功完成所有关卡，荣登王者宝座，请领取属于您的财富吧！");
			$(".popup_tips2 .popup_main").css({
				"margin-top":-$(".popup_tips2 .popup_main").height()/2,
				"opacity":1
			})
			return;
		}
		getQustion(nowBank,10);
		startOneQuestion();
	}
	/*
	*终止游戏函数
	*/ 
	function endGame(){
		showPage(2);
	}

    function load_sound(url_data) {

        var req = new XMLHttpRequest();

        req.open('GET', url_data, true);

        req.responseType = 'arraybuffer';

        req.onload = function() {

            con.decodeAudioData(req.response, function(buffer){

                var source = con.createBufferSource();

                source.buffer = buffer;

                source.connect(con.destination);

                source.start(0);

            },function (e) {

                console.info('错误');

            });

        }

        req.send();

    }
})