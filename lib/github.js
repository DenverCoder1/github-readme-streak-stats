// lib/github.js
export class GitHubAPI {
  constructor(token = null) {
    this.token = token || process.env.GITHUB_TOKEN;
    this.endpoint = 'https://api.github.com/graphql';
  }
  
  async query(query, variables = {}) {
    const headers = {
      'Content-Type': 'application/json',
      'User-Agent': 'GitHub-Readme-Streak-Stats-Vercel'
    };
    
    if (this.token) {
      headers['Authorization'] = `bearer ${this.token}`;
    }
    
    const response = await fetch(this.endpoint, {
      method: 'POST',
      headers,
      body: JSON.stringify({ query, variables })
    });
    
    if (!response.ok) {
      throw new Error(`GitHub API error: ${response.status}`);
    }
    
    return await response.json();
  }
  
  async getUserContributions(username, year) {
    const query = `
      query($username: String!) {
        user(login: $username) {
          createdAt
          contributionsCollection(from: "${year}-01-01T00:00:00Z", to: "${year}-12-31T23:59:59Z") {
            contributionCalendar {
              weeks {
                contributionDays {
                  contributionCount
                  date
                }
              }
            }
          }
        }
      }
    `;
    
    const result = await this.query(query, { username });
    
    if (result.errors) {
      throw new Error(result.errors[0]?.message || 'GitHub API error');
    }
    
    return result.data.user;
  }
}