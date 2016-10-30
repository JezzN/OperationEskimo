<div class="character class-{{ $member['character']['character']['class'] }}">
    <div class="character-section">
        <a target="_blank" href="http://eu.battle.net/wow/en/character/ragnaros/{{ $member['character']['character']['name']  }}/advanced">{{ $member['character']['character']['name'] }}</a> ({{ isset($member['character']['character']['spec']) ? $member['character']['character']['spec']['name']: '?' }})

    </div>
    <div class="character-section">
        {{ $items->averageForCharacter($member) }}
    </div>
</div>
