<xml>
    <ToUserName><![CDATA[{{ $postObject->FromUserName }}]]></ToUserName>
    <FromUserName><![CDATA[{{ $postObject->ToUserName }}]]></FromUserName>
    <CreateTime>{{ time() }}</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[{{ $content }}]]></Content>
    <FuncFlag>0</FuncFlag>
</xml>