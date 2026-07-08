@extends('admin.game-cards.form', ['action' => route('admin.game-cards.store', $set), 'method' => 'POST', 'card' => null, 'set' => $set])
