Given(/^user is on the login page$/) do
	expect(page).to have_selector('login-username')
	expect(page).to have_selector('login-password')
end

When(/^user types the right password and username$/) do
	find('#login-username').set()
	find('#login-password').set()
end

Then(/^user can login$/)do
	expect(current_path).to eq '/'
end 
