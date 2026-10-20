async function checkAuth() {
  try {
    const response = await fetch('backend/check_session.php');
    const result = await response.json();
    return result.success ? result.user : null;
  } catch (error) {
    return null;
  }
}

async function requireAuth() {
  const user = await checkAuth();
  if (!user) {
    window.location.href = 'login.html';
  }
  return user;
}