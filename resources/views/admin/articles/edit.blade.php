@extends('admin.articles.form', ['action' => route('admin.articles.update', $article), 'method' => 'PUT', 'article' => $article])
