@extends('layouts.email')

@section('content')
    <table bgcolor="#fff" border="0" cellpadding="0" cellspacing="0" style="margin: 0;padding: 50px 0; width: 100%;height: 100vh;">
        <tr>
            <td>
                <center style="max-width: 600px; width: 100%;margin: 0 auto;">
                    <table border="0" cellpadding="0" cellspacing="0" style="margin: 0;padding: 0; width: 100%;text-align: center;">
                        <tr>
                            <td>
                                <a href="{{url()->full()}}" style="display: inline-block;" target="blank">
                                    <img src="{{ $message->embed(public_path() . '/assets/essential/logo.jpg') }}" alt="" border="0" style="display: block;object-fit: contain;height: 130px;width: 180px;">
                                </a>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span style="margin-top: 30px;display: block;font-size: 30px;font-weight: 500;">Congratulations!</span>
                            </td>
                        </tr>
                        <tr style="display: block;margin-top: 20px;">
                            <td>
                                <span style="font-size: 18px;">Thank you for signing up for THE HOT MEAL! We're so happy you're taking charge of your meal planning goals.</span>
                            </td>
                        </tr>

                        <tr style="margin-top: 40px;display: block;">
                            <td style="display: inline-block">
                                <a href="{{ url("activate-account/" . $user->activation_token) }}" style="background: #df2c65;color: #fff;width: 240px;display: inline-block;height: 50px;line-height: 48px;border-radius: 7px;font-size: 18px;">Activate your account</a>
                            </td>
                        </tr>
                    </table>
                </center>
            </td>
        </tr>
    </table>

@endsection
