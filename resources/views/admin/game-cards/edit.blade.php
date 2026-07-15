@extends('admin.game-cards.form', ['action' => route('admin.game-cards.update', $card), 'method' => 'PUT', 'card' => $card, 'set' => $set])
