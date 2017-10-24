var blackCurtain,//黑色背景
    writeCurtain,//提示框大背景
    contentDiv,//提示框
    textDiv,//提示文字显示区
    topDiv,//提示框头部
    ensureDiv,//确认按钮
    cancleDiv,//取消按钮
    bodyNode,//body标签节点
    ensure,//确认回调函数
    cancle,//取消回调函数
    ensureText,
    cancleText;
var blackShow = function (is_show)  
    {
    	if (is_show == 0)
    	{
    		blackCurtain.remove();
    	} else {
    		blackCurtain = document.createElement('div');
            blackCurtain.setAttribute('class','blackCurtain');
            blackCurtain.style.height = document.body.clientHeight;
            bodyNode.appendChild(blackCurtain);
    	}
    }
var contentShow = function (is_show,remind)  
    {
    	if (is_show == 0)
    	{
    		contentDiv.remove();
    	} else {
    		writeCurtain = document.createElement('div');
    		contentDiv = document.createElement('div');
    		topDiv = document.createElement('div');
    		textDiv = document.createElement('div');
    		ensureDiv = document.createElement('div');
    		cancleDiv = document.createElement('div');
            writeCurtain.setAttribute('class','writeCurtain');
            contentDiv.setAttribute('class','contentDiv');
            topDiv.setAttribute('class','topDiv');
            topDiv.innerHTML = '提示';
            textDiv.setAttribute('class','textDiv');
            textDiv.innerHTML = remind;
            ensureDiv.setAttribute('class','ensureDiv');
            ensureDiv.innerHTML = ensureText;
            addEnsureEvent(ensureDiv);
            cancleDiv.setAttribute('class','cancleDiv');
            cancleDiv.innerHTML = cancleText;
            addCancleEvent(cancleDiv);
            writeCurtain.style.height = document.body.clientHeight;
            bodyNode.appendChild(writeCurtain);
            writeCurtain.appendChild(contentDiv);
            contentDiv.appendChild(topDiv);
            contentDiv.appendChild(textDiv);
            contentDiv.appendChild(ensureDiv);
            contentDiv.appendChild(cancleDiv);
    	}
    }
var addEnsureEvent = function (node)
	{
		if (node.attachEvent) 
		{ //IE 中
	        node.attachEvent('onclick',ensureEvent); 
	    } else {
	   //firefox googleChorme
	      node.addEventListener('click',ensureEvent, true);
	    }
	}
var addCancleEvent = function (node)
	{
		if (node.attachEvent) { //IE 中
	        node.attachEvent('onclick',cancleEvent); 
	    } else {
	   //firefox googleChorme
	      node.addEventListener('click',cancleEvent, true);
	    }
	}
var ensureEvent = function ()
	{
		blackCurtain.remove();
		contentDiv.remove();
		writeCurtain.remove();
		ensure();
	}
var cancleEvent = function ()
	{
		blackCurtain.remove();
		contentDiv.remove();
		writeCurtain.remove();
		cancle();
	}
var defaultOp = function()
	{
		blackCurtain.remove();
		contentDiv.remove();
		writeCurtain.remove();
	}
var is_sure=function(opt)
	{
		if (typeof opt != "object") {
			alert('参数错误!');
			return false;
		}
		remind = opt.remind;
		ensure = (opt.ensure)?opt.ensure:defaultOp;
		cancle = (opt.cancle)?opt.cancle:defaultOp;
		ensureText = (opt.ensureText)?opt.ensureText:"确认";
		cancleText = (opt.cancleText)?opt.cancleText:"取消";
		bodyNode = document.getElementsByTagName('body')[0];
		eval(blackShow(1));
		eval(contentShow(1,remind));
	}