@extends('pdo::layouts.master') 
@section('template_title')
    {{ trans('installer_messages.welcome.templateTitle') }}
@endsection

@section('title')
    {{ trans('installer_messages.welcome.title') }}
@endsection

@section('container')
    <p class="text-center">
      {{ trans('installer_messages.welcome.installedmessage') }}
    </p>
    <p class="text-center">
      <a href="{{ route('/') }}" class="button">
        {{ trans('installer_messages.welcome.backtohome') }}
        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
      </a>
    </p>
@endsection
