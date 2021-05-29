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
                                <span style="margin-top: 30px;display: block;font-size: 30px;font-weight: 500;">Your statistics for last week!</span>
                            </td>
                        </tr>


                        <tr style="margin-top: 20px;margin-bottom: 20px;display: block;">
                            <td style="display: inline-block">
                                <span style="color:#FF667B; font-size: 21px; margin-right: 10px;"> {{ $stats['start'] }} </span>
                                <span style="font-weight: 600;font-size: 21px">to</span>
                                <span style="color:#FF667B;font-size: 21px; margin-left: 10px;"> {{ $stats['end'] }} </span>
                            </td>
                        </tr>




                        <tr style="margin-bottom: 15px; margin-top: 15px; display: block; text-align: left;">
                            <td style="display: inline-block">
                                <span class="two-row" style="font-weight: 600;margin-right: 10px; font-size: 21px;">Weekly calories plan:</span>
                                <span style="color:#FF667B; font-size: 21px;white-space: nowrap;"> {{ number_format($stats['plan']) }} Cal </span>
                            </td>
                        </tr>

                        <tr style="margin-bottom: 15px; margin-top: 15px; display: block; text-align: left;">
                            <td style="display: inline-block">
                                <span class="two-row" style="font-weight: 600;margin-right: 10px; font-size: 21px;">Your actual weekly calories:</span>
                                <span style="color:#FF667B; font-size: 21px;white-space: nowrap;"> {{ number_format($stats['fact']) }} Cal </span>
                            </td>
                        </tr>


                        <tr style="margin-bottom: 15px; margin-top: 15px; display: block; text-align: left;">
                            <td style="display: inline-block">
                                <span class="two-row" style="font-weight: 600;margin-right: 10px; font-size: 21px;">Your daily calories plan:</span>
                                <span style="color:#FF667B; font-size: 21px;white-space: nowrap;"> {{ $stats['netCal'] }} Cal </span>
                            </td>
                        </tr>

                        <tr style="margin-bottom: 15px; margin-top: 15px; display: block; text-align: left;">
                            <td style="display: inline-block">
                                <span class="two-row" style="font-weight: 600;margin-right: 10px; font-size: 21px;">Your weekly shopping:</span>
                                <span style="color:#FF667B; font-size: 21px;white-space: nowrap;">{{ config('thehotmeal.currencySm') }}{{ $stats['spent'] }} </span>
                            </td>
                        </tr>


                        <tr style="margin-bottom: 15px; margin-top: 15px; display: block; text-align: left; border-top: 1px solid black; padding-top: 15px">
                            <td style="display: inline-block">
                                <span style="font-weight: 600;margin-right: 10px; font-size: 21px;">
                                    Your weekly result:
                                </span>

                                <span style="font-weight: 400;margin-right: 10px; font-size: 21px;">
                                    {{ $stats['result'] }}
                                </span>
                            </td>
                        </tr>

                        <tr style="margin-bottom: 15px; margin-top: 15px; display: block; text-align: left; background: #F7F7F7; padding: 25px; margin-left: -20px; margin-right: -20px;">
                            <td style="display: block; padding: 10px 0;">

                                <span class="two-row" style="font-weight: 600; font-size: 21px;">
                                    Your weight:
                                </span>

                                <span style="font-weight: 400; font-size: 21px;  color:#FF667B;white-space: nowrap;">
                                    {{ $stats['weight'] }} Lb
                                </span>

                            </td>

                            <td style="display: block; padding: 10px 0;">

                                <span class="two-row" style="font-weight: 600; font-size: 21px;">
                                    Your goal:
                                </span>

                                <span style="font-weight: 400; font-size: 21px; color:#FF667B;white-space: nowrap;">
                                    @if($stats['goal'] === 0) {
                                    Maintain weight
                                    @elseif($stats['goal'] < 0)
                                        To lose {{abs($stats['goal'])}} Lb
                                    @else
                                        To gain {{abs($stats['goal'])}} Lb
                                    @endif
                                </span>

                            </td>
                        </tr>



                        <tr style="margin-top: 40px;display: block;">
                            <td style="display: inline-block">
                                <a href="{{url('/meal-planner')}}" style="background: #df2c65;color: #fff;width: 240px;display: inline-block;height: 50px;line-height: 48px;border-radius: 7px;font-size: 18px;">View your meal plan</a>
                            </td>
                        </tr>
                    </table>
                </center>
            </td>
        </tr>
    </table>
@endsection
