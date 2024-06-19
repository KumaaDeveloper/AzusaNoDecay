## General
AzusaNoDecay is a Pocketmine plug-in that functions so that leaves and farms cannot be destroyed.

## Features
- Leaves will not be destroyed by either server ticks or breaks
- The farm will not be destroyed whether it is stepped on by the player or breaks
- Custom world

## Command
Commands | Default | Permission
--- | --- | ---
`/nodecay` | Op | nodecay.use
  
## Configuration
```yaml
# AzusaNoDecay Configuration

# Configuration for all leaves so they don't get crushed
NoDecay-Leaves: true
AllWorld-NoDecay-Leaves: false
World-NoDecay-Leaves: ["lobby", "world"]

# Configuration for farmland not to be destroyed
NoDecay-Farmland: true
AllWorld-NoDecay-Farmland: false
World-NoDecay-Farmland: ["lobby", "world"]

# Configuration for farms like wheat, carrot, potato, beetroot so they don't get crushed
NoDecay-Crops: true
AllWorld-NoDecay-Crops: false
World-NoDecay-Crops: ["lobby", "world"]

# Configuration for farms such as wheat seeds, melon seeds, pumpkin seeds, beetroot seeds so that they are not crushed
NoDecay-Seed: true
AllWorld-NoDecay-Seed: false
World-NoDecay-Seed: ["lobby", "world"]
```

## Todo
- [ ] Not supporting Torchflower
- [ ] Not supporting PitcherPod
