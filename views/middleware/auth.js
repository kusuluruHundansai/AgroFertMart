module.exports.isLoggedIn = (req, res, next) => {
  if (!req.session.user) {
    req.session.error = "You must be logged in first!";
    return res.redirect('/auth/login');
  }
  next();
};

module.exports.isAdmin = (req, res, next) => {
  if (!req.session.user || req.session.user.role !== 'admin') {
    req.session.error = "You do not have permission to do that!";
    return res.redirect('/');
  }
  next();
};
