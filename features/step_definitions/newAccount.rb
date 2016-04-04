Given (/^user is on the main UI$/) do
	visit('http://localhost')
	find('').set('')
	find('').set('')
	page.execute_script("$")
end

When(/^user clicks the account button$/)
	if(page.has_button?('login'))
		page.click_button(login)
	end
end

Then(/^user can create new account)
end