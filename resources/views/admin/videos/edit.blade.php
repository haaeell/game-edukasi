@extends('admin.videos.form', ['action' => route('admin.videos.update', $video), 'method' => 'PUT', 'video' => $video])
