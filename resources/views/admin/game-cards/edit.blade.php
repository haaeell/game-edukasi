@extends('admin.game-cards.form', ['action' => route('admin.game-cards.update', [$set, $card]), 'method' => 'PUT', 'card' => $card, 'set' => $set])
