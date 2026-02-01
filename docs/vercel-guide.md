# Vercel deplyment guide

## Prerrequisites
- Account in [Vercel](https://vercel.com) (free)
- Account in GitHub
- This repository fork

## Methode 1: Deploy with one click (RECOMENDED)
[![Deploy with Vercel](https://vercel.com/button)](https://vercel.com/new/clone?repository-url=https://github.com/FlavioKde/github-readme-streak-stats)

## Method 2: Manual deployment
```bash
# 1. Clone this fork
git clone https://github.com/FlavioKde/github-readme-streak-stats.git

# 2. Install Vercel CLI
npm i -g vercel

# 3. Deployment init
vercel

# 4. Follow the instruccions
# 5. To producción:
vercel --prod