{{ $count = count($content) > 10 ?? 10; }}
<xml>
    <ToUserName><![CDATA[{{ $item->FromUserName }}]]></ToUserName>
    <FromUserName><![CDATA[{{ $item->ToUserName }}]]></FromUserName>
    <CreateTime>{{ time() }}</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount><![CDATA[{{ $count }}]]></ArticleCount>
    <Articles>
    @foreach($content as $item)
        <item>
            <Title><![CDATA[{{ $item['Title'] }}]]></Title>
            <Description><![CDATA[{{ $item['Description'] }}]]></Description>
            <PicUrl><![CDATA[{{ $item['PicUrl'] }}]]></PicUrl>
            <Url><![CDATA[{{ $item['Url'] }}]]></Url>
        </item>
    @endforeach
    </Articles>
    <FuncFlag>0</FuncFlag>
</xml>