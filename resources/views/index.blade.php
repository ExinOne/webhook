@extends('layouts.app')

@section('content')
    <div class="container">
        @if (!empty($access_token))
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card border-primary">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h3>Access Token</h3>
                                    <div>{{ $access_token }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card border-danger">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <h3>Example</h3>
                                    <code>curl {{ env('APP_URL') }}/api/send?access_token={{ $access_token }} -XPOST -H 'Content-Type: application/json' -d '{"category":"PLAIN_TEXT","data":"Hello World!"}'</code>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @else
            <div class="row justify-content-center mt-3">
                <div class="col-md-8">
                    <div class="card border-danger">
                        <div class="card-body">
                            <div class="row">
                                <div class="col">
                                    <div>您不是群主，无法获取 <code>Access Token</code></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="row justify-content-center mt-3">
            <div class="col-md-8">
                <div class="card border-success">
                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                <h3>使用指南</h3>
                                <ul class="mb-0">
                                    <li>将机器人拉到群里，<a href="https://mixin.one/codes/4d792128-1db8-4baf-8d90-d0d8189a4a7e">7000000012</a>，群主打开机器人可以获取到 <code>access_token</code></li>
                                    <li>不拉到群里也能用，就是消息直接发给自己</li>
                                    <li><code>category</code> 为 Mixin 消息类型，<code>data</code> 是消息内容，具体参考 <a href="https://developers.mixin.one/api/beta-mixin-message/websocket-messages/">Mixin 文档</a></li>
                                    <li>目前支持类型 <code>PLAIN_TEXT</code>、<code>PLAIN_CONTACT</code>、<code>APP_BUTTON_GROUP</code>、<code>APP_CARD</code>，完善后所有类型都会支持</li>
                                    <li>Webhook 机器人只做转发，不保存消息，消息发送结果同步返回，请自行验证状态</li>
                                    <li>机器人不保存任何消息的，没有安全问题，如果不放心用可以等代码开源自行部署，代码会在近期完善后开源</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
