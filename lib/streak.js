// lib/streak.js
export class StreakCalculator {
  static parseContributions(userData) {
    if (!userData?.contributionsCollection?.contributionCalendar?.weeks) {
      return {};
    }
    
    const contributions = {};
    const weeks = userData.contributionsCollection.contributionCalendar.weeks;
    
    weeks.forEach(week => {
      week.contributionDays.forEach(day => {
        contributions[day.date] = day.contributionCount;
      });
    });
    
    return contributions;
  }
  
  static calculateDailyStreak(contributions) {
    const dates = Object.keys(contributions).sort();
    
    if (dates.length === 0) {
      return {
        totalContributions: 0,
        firstContribution: null,
        longestStreak: { start: null, end: null, length: 0 },
        currentStreak: { start: null, end: null, length: 0 }
      };
    }
    
    // Lógica básica (mañana la completamos)
    const total = Object.values(contributions).reduce((a, b) => a + b, 0);
    
    return {
      totalContributions: total,
      firstContribution: dates[0],
      longestStreak: { start: dates[0], end: dates[dates.length-1], length: dates.length },
      currentStreak: { start: dates[0], end: dates[dates.length-1], length: dates.length }
    };
  }
}