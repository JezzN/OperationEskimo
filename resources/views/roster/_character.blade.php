<div class="character class-{{ $member->class }}">
    <div class="character-section">
        <a target="_blank" href="http://eu.battle.net/wow/en/character/ragnaros/{{ $member->character_name  }}/advanced">{{ $member->character_name }}</a>

    </div>
    <div class="character-section">
        {{ $member->average_item_level_equipped }} ({{ $member->average_item_level }})
    </div>
</div>
