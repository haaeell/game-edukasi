@extends('admin.game-card-sets.form', ['action' => route('admin.game-card-sets.update', $set), 'method' => 'PUT', 'set' => $set])
