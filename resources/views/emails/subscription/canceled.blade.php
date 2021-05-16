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
                                <span style="margin-top: 30px;display: block;font-size: 30px;font-weight: 500;">
                                    Your subscription has been cancelled. We are sad to see you go.
                                </span>
                            </td>
                        </tr>
                        <tr style="display: flex;margin-top: 20px;justify-content: center;margin-bottom: 20px;">
                            <td>
                                <span style="font-size: 18px;">Hope, you decide to come back soon and enjoy our service.</span>
                            </td>
                        </tr>


                        <tr style="margin-top: 30px;display: block;">
                            <td style="display: inline-block">
                                <span style="font-size: 18px;margin-right: 10px;">
                                 If you'd like to start planning your meal goals again, you can reactivate your subscription any time.
                                </span>
                            </td>
                        </tr>

                        <tr style="margin-top: 40px;display: block;">
                            <td style="display: inline-block">
                                <a href="{{ url("/meal-planner/settings") }}" style="background: #df2c65;color: #fff;width: 240px;display: inline-block;height: 50px;line-height: 48px;border-radius: 7px;font-size: 18px;">Renew</a>
                            </td>
                        </tr>


                    </table>
                </center>
            </td>
        </tr>
    </table>

@endsection
