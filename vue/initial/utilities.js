let that = this, toString = Object.prototype.toString;

export default {
    Log() {
        for (var i = 0; i < arguments.length; i++) {
            console.log(arguments[i])
        }
    },
    fetchErrors(res_errors) {
        var errors = {};
        for (var key in res_errors) {
            errors[key] = res_errors[key][0];
        }
        return errors
    },
    findPos(obj) {
        let limit = 1000, inc = 0, posX, posY;
        if (!obj) {
            return {x: 0, y: 0};
        }
        posX = obj.offsetLeft || 0;
        posY = obj.offsetTop || 0;
        while (obj.offsetParent && inc <= limit) {
            if (obj == document.getElementsByTagName('body')[0]) {
                break;
            } else {
                posX += obj.offsetParent.offsetLeft;
                posY += obj.offsetParent.offsetTop;
                obj = obj.offsetParent;
            }
            inc++;
        }
        return {x: posX, y: posY};
    },
    offsetTop(id) {
        var el = document.getElementById(id);
        if (el) {
            return el.offsetTop
        }
        return 0
    },
    scrollToTop(t) {
        window.scrollTo(0, t)
    },
    escapeArray(objs, target) {
        var str = '', i = 1;
        objs.filter(e => {
            str += `${e[target]}${(i < objs.length) ? ", " : ""}`;
            i++;
        });
        return str
    },
    removeArrayItem(arr) {
        let what, a = arguments, L = a.length, ax, key;
        if (L > 1 && arr.length) {
            what = a[1];
            key = a[2];
            if (key) {
                arr = arr.filter(d => {
                    return (String(d[key]).indexOf(what) === -1);
                });
            } else {
                while ((ax = arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
        }
        return arr;
    },
    scrollToElementId(id) {
        var element = document.getElementById(id);
        if (element) {
            setTimeout(() => {
                element.scrollIntoView();
            }, 200)
        }
    },
    scrollValidate(id, more = 0) {
        setTimeout(() => {
            $("html,body").animate({scrollTop: this.offsetTop(id) + more}, "slow")
        }, 200)
    },
    scrollToY(id = 'main-container', y = 0) {
        let q = (id === 'html,body') ? 'html,body' : `#${id}`;
        $(q).animate({
            scrollTop: y
        }, 100);
    },
    animateScrollToY(id = 'main-container', y = 0) {
        let q = (id === 'html,body') ? 'html,body' : `#${id}`;
        $(q).animate({
            scrollTop: y
        }, 300);
    },
    /*
    @todo scroll to actual pos and let user can scroll when the event is fired up
     */
    scrollActually(id, more = 0) {
        this.setSession('scrollClick', 'yes');
        this.setSession('scrollId', id);
        var el = document.getElementById(id);
        var old_scroll = this.hasSession('pageYOffset') ? this.toInt(this.getSession('pageYOffset')) : -1;
        var old_id = this.getSession('scrollId');
        var pos_scroll = this.toInt(window.pageYOffset);
        if (old_scroll === pos_scroll && old_id === id) {
            this.setSession('scrollClick', 'no');
            return
        }
        setTimeout(() => {
            $("html,body").animate({scrollTop: this.findPos(el)[1] + more}, "slow")
        }, 200);
        let self = this;
        $(window).scroll(function () {
            this.clearTOut($.data(this, 'scrollTimer'));
            $.data(this, 'scrollTimer', setTimeout(function () {
                if (self.getSession('scrollClick') === 'yes') {
                    self.setSession('pageYOffset', self.toInt(window.pageYOffset))
                }
                self.setSession('scrollClick', 'no')
            }, 200))
        });
    },
    clearTOut(t) {
        window.clearTimeout(t);
    },
    myScrollValidate(id, more = 0) {
        setTimeout(() => {
            this.scrollToTop(this.offsetTop(id) + more)
        }, 200)
    },
    // ValidateException(message) {
    //     this.message = message;
    //     this.type = 'Visibility';
    //     this.name = 'Validate Exception';
    // },
    addDataForm(acceptFields = [], form = new FormData(), obj = {}) {
        acceptFields.forEach(keyVal => {
            if (this.isKeyExists(keyVal, obj)) {
                if (obj[keyVal] == null || typeof obj[keyVal] === 'undefined') {
                    obj[keyVal] = '';
                }
                form.append(keyVal, obj[keyVal])
            }
        });
    },
    arrDel(array, i, l) {
        array.splice(i, l)
    },
    /**
     * @return {Promise}
     */
    Validate(data, rules, opts = {}) {
        /**@My Validation Method V1.0 */
            //@usage:'email': ['email', { min: 6 }, { max: 40 }, 'required'],
        var ol = Object.keys(rules);
        var errorsObj = {};
        var keyRuleNames = this.keyNames(rules);
        var types = ['array', 'object', 'string'];
        for (var i = 0; i < ol.length; i++) {
            for (var j = 0; j < rules[ol[i]].length; j++) {
                var rule = rules[ol[i]][j],
                    value = data[ol[i]],
                    mimes = [], c = 1000, size = 0,
                    isFile = (value && value.file && value.file.size), isType,
                    attribName = `${ol[i].replace(/_/g, " ")}`;
                rules[ol[i]].filter(d => {
                    if (!this.isEmptyVar(d.mimes)) {
                        mimes = String(d.mimes).split(',');
                    }
                });
                if (value == null || typeof value === 'undefined') {
                    value = ''
                } else {
                    value = data[ol[i]]
                }
                /**@Required Section **/
                if ('required' === rule && (value === '' || value === null) && keyRuleNames[ol[i]] === ol[i]) {
                    errorsObj[ol[i]] = `The ${attribName} field is required.`;
                } else if (typeof value === 'object' && 'required' === rule
                    && this.isArray(value) && value.length <= 0 && keyRuleNames[ol[i]] === ol[i]) {
                    errorsObj[ol[i]] = `The ${attribName} field is required.`;
                } else if (mimes.length > 0 && 'required' === rule && !isFile) {
                    errorsObj[ol[i]] = `The ${attribName} field is required.`;
                }
                /**@Required Section **/

                /**@RequiredObject Section **/
                if (rule.required) {
                    if (rule.required.when && this.accessObjectLevels(data, rule.required.when) === rule.required.equals && keyRuleNames[ol[i]] === ol[i] && (value === '' || value === null ||
                        (typeof value === 'object' && this.isArray(value) && value.length <= 0))) {
                        errorsObj[ol[i]] = `The ${attribName} field is required.`;
                    }
                }
                /**@RequiredObject Section **/
                /**@RuleType Section **/
                isType = types.filter(f => {
                    return f === rule;
                })[0];
                if (isType && isType === 'array' && typeof value !== 'object') {
                    errorsObj[ol[i]] = `The ${attribName} field is invalid data type.`;
                } else if (isType && typeof value !== isType && isType !== 'array') {
                    errorsObj[ol[i]] = `The ${attribName} field is invalid data type.`;
                }
                /**@RuleType Section **/

                /**@Email Section **/
                if ('email' === rule && (value !== '' && value !== null) && keyRuleNames[ol[i]] === ol[i]) {
                    if (!this.isEmail(value)) {
                        errorsObj[ol[i]] = `Your ${rule} address is invalid.`;
                    }
                }
                /**@ConfirmPassword Section **/
                if ('confirm' === rule && (value !== '' && value !== null) && keyRuleNames[ol[i]] === ol[i]) {
                    var password_confirmation = data['password_confirmation'];
                    (typeof password_confirmation === undefined) ? password_confirmation = '' : password_confirmation = data['password_confirmation'];
                    (typeof password_confirmation === 'undefined') ? password_confirmation = '' : password_confirmation = data['password_confirmation'];
                    if (value !== data['password_confirmation'] && password_confirmation !== '' && password_confirmation !== null) {
                        errorsObj['password_confirmation'] = `Your confirmation password and ${attribName} do not match.`;
                    }
                }
                if ('phone number' === rule && (value !== '' && value !== null) && keyRuleNames[ol[i]] === ol[i]) {
                    if (!this.isPhoneNumber(value)) {
                        errorsObj[ol[i]] = `Your ${attribName} is invalid.`;
                    }
                }
                if ('number' === rule && (value !== '' && value !== null) && keyRuleNames[ol[i]] === ol[i]) {
                    if (!this.isNumber(value)) {
                        errorsObj[ol[i]] = `The ${attribName} field must be a number.`;
                    }
                }
                if ('>now' === rule && (value !== '' && value !== null) && keyRuleNames[ol[i]] === ol[i]) {
                    if (!this.isGreaterThanNow(value)) {
                        errorsObj[ol[i]] = `The ${attribName} field may not less then now.`;
                    }
                }

                if ('emails' === rule && (value !== '' && value !== null) && keyRuleNames[ol[i]] === ol[i]) {
                    var emails = this.rmSpaces(value.toString()).split(',');
                    emails = emails.filter(e => {
                        return e !== ""
                    });
                    if (emails.length <= 0) {
                        errorsObj[ol[i]] = `The emails field is invalid.`;
                    } else {
                        for (var em = 0; em < emails.length; em++) {
                            if (!this.isEmail(emails[em])) {
                                errorsObj[ol[i]] = `Some email address is invalid.`;
                                break;
                            }
                        }
                    }
                }
                /** @Check if a file **/
                if (rule.mimes && mimes.length > 0) {
                    if (isFile && keyRuleNames[ol[i]] === ol[i] && !this.isFileExtAllowed(value.file, mimes)) {
                        errorsObj[ol[i]] = `The ${attribName} must be a file of type: ${mimes.join(", ")}.`;
                    }
                }
                /** @Check file size and string characters **/
                if (mimes.length > 0) {
                    if (isFile) {
                        size = this.formatBytesTo(value.file.size, "KB", 2);
                    }
                }
                if (rule.min) {
                    if (mimes.length > 0 && rule.min > size && keyRuleNames[ol[i]] === ol[i] && isFile) {
                        errorsObj[ol[i]] = `The ${attribName} may not be less than ${this.formatBytes(rule.min * c)}.`;
                    } else if (rule.min > value.length && keyRuleNames[ol[i]] === ol[i] && (value !== '' && value !== null)) {
                        errorsObj[ol[i]] = `The minimum ${attribName} is ${rule.min} characters.`;
                    }
                }
                if (rule.max) {
                    if (mimes.length > 0 && rule.max < size && keyRuleNames[ol[i]] === ol[i] && isFile) {
                        errorsObj[ol[i]] = `The ${attribName} may not be greater than ${this.formatBytes(rule.max * c)}.`;
                    } else if (rule.max < value.length && keyRuleNames[ol[i]] === ol[i] && (value !== '' && value !== null)) {
                        errorsObj[ol[i]] = `The  maximum ${attribName} is ${rule.max} characters.`;
                    }
                }
            }
        }
        return new Promise((resolve, reject) => {
            if (this.isEmptyObject(errorsObj)) {
                resolve({success: true, errors: {}, validated: data})
            } else {
                // this.scrollValidate(`validate-${Object.keys(errorsObj).length > 0 ? Object.keys(errorsObj)[0] : ''}`,
                //     this.isKeyExists("scrollTop", opts) ? opts.scrollTop : 0);
                reject({success: false, errors: errorsObj});
            }
        });
        // if (this.isEmptyObject(errorsObj)) {
        //     callback({success: true, errors: {}});
        //     return true
        // }
        // this.scrollValidate(`validate-${Object.keys(errorsObj).length > 0 ? Object.keys(errorsObj)[0] : ''}`,
        //     this.isKeyExists("scrollTop", opts) ? opts.scrollTop : 0);
        // callback({success: false, errors: errorsObj});
        // throw new this.ValidateException('Please complete all required fields!.');
    },
    createFileBase64(file, callback) {
        var reader = new FileReader();
        reader.onload = function (e) {
            callback(e.target.result)
        };
        reader.readAsDataURL(file)
    },
    /**
     * @return {boolean}
     */
    IsImageFileExtensions(file) {
        var ext = this.getFileExtension(this.getFileNameFromFile(file)).toLowerCase();
        return ext === 'jpg' || ext === 'jpeg' || ext === 'png' || ext === 'svg' || ext === 'gif';
    },
    /**
     * @return {boolean}
     */
    IsCVFileExtensions(file) {
        var ext = this.getFileExtension(this.getFileNameFromFile(file)).toLowerCase();
        return ext === 'pdf' || ext === 'doc' || ext === 'docx';

    },
    getFileExtension(filename) {
        return filename.split('.').pop();
    },
    getFileExtensionWithFullPath(path) {
        var basename = path.split(/[\\/]/).pop(), // extract file name from full path ...
            // (supports `\\` and `/` separators)
            pos = basename.lastIndexOf("."); // get last position of `.`

        if (basename === "" || pos < 1) // if file name is empty or ...
            return ""; //  `.` not found (-1) or comes first (0)

        return basename.slice(pos + 1); // extract extension ignoring `.`
    },
    getFileNameFromPath(fullPath = "") {
        return fullPath.replace(/^.*[\\\/]/, '')
    },
    getFileNameFromFile(file) {
        return file.name
    },
    getFileSize(file) {
        return file.size
    },
    formatBytes(a, b) {
        if (0 == a) return "0 Bytes";
        var c = 1000,
            d = b || 2,
            e = ["Bytes", "KB", "MB", "GB", "TB", "PB", "EB", "ZB", "YB"],
            f = Math.floor(Math.log(a) / Math.log(c));
        return parseFloat((a / Math.pow(c, f)).toFixed(d)) + " " + e[f]
    },
    formatBytesTo(a, t, b) {
        if (0 == a) return 0;
        var c = 1000,
            d = b || 2,
            e = [{u: "BYTES", p: 0}, {u: "KB", p: 1}, {u: "MB", p: 2},
                {u: "GB", p: 3}, {u: "TB", p: 4}, {u: "PB", p: 5},
                {u: "EB", p: 6}, {u: "ZB", p: 7}, {u: "YB", p: 8}],
            f = 0;

        e.filter(y => {
            if (y.u === String(t).toUpperCase()) {
                if (y.p === 0) {
                    f = parseFloat(a.toFixed(d));
                } else {
                    f = parseFloat((a / Math.pow(c, y.p)).toFixed(d));
                }
            }
        });
        return f;
    },
    parseJSON(dataString) {
        if (/^[\],:{}\s]*$/.test(dataString.replace(/\\["\\\/bfnrtu]/g, '@').replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']').replace(/(?:^|:|,)(?:\s*\[)+/g, ''))) {
            return JSON.parse(dataString)
        }
        return {};
    },
    isEmptyObject(obj) {
        for (var prop in obj) {
            if (obj.hasOwnProperty(prop))
                return false;
        }
        return JSON.stringify(obj) === JSON.stringify({});
    },
    isObjectEquals(own, other) {
        let k = Object.keys(own), j = Object.keys(other);
        if (k.length === j.length) {
            for (let p = 0; p < k.length; p++) {
                if (this.isKeyExists(k[p], own) && this.isKeyExists(j[p], other)) {
                    if (own[k[p]] !== other[j[p]]) {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
        return true;
    },
    isEmptyVar(exp) {
        return exp === '' || exp === null || typeof exp === 'undefined';
    },
    isKeyExists(key, obj) {
        return obj.hasOwnProperty(key);
    },
    keyNames(obj) {
        var names = {};
        Object.keys(obj).forEach(function (key) {
            names[key] = key;
        });
        return names;
    },
    isFileExtAllowed(f, e = []) {
        f = f || {name: ''};
        let c = false, n;
        n = this.getFileExtension(f.name);
        e.filter(d => {
            if (String(n).toLowerCase() === String(d).toLowerCase()) {
                c = true;
            }
        });
        return c;
    },
    isEmail(email) {
        var e = String(email).toLowerCase();
        var rgx_space = /\s/g;
        if (rgx_space.test(e)) {
            return false;
        }
        var rgx_email = /^[a-z0-9]+(?:_[a-z0-9]+)?(?:\.[a-z0-9]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])$/
        return rgx_email.test(e);
    },
    isPhoneNumber(tel) {
        tel = tel.replace(/\s/g, '');
        var re = /^\+.[0-9]+$|^[0-9]+$/g;
        return re.test(tel);
    },
    isNumber(n) {
        n = n.toString().replace(/\s/g, '');
        var re = /^[0-9]+(\.[0-9]+)?$/;
        return re.test(n);
    },
    isArray(obj) {
        return Object.prototype.toString.call(obj) === '[object Array]';
    },
    isGreaterThanNow(date_string) {
        var Now = new Date().getTime();
        var chDay = new Date(date_string).getTime();
        return Now <= chDay;
    },
    rmSpaces(n) {
        n = n.replace(/\s/g, '')
        return n
    },
    Location(href, delay) {
        if (delay !== null) {
            setTimeout((e) => {
                window.location.href = baseUrl + href
            }, delay)
        } else {
            window.location.href = baseUrl + href
        }
    },
    ipv4Url(str) {
        return RegExp([
            '^https?:\/\/([a-z0-9\\.\\-_%]+:([a-z0-9\\.\\-_%])+?@)?',
            '((25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])\\.){3}(25[0-5]|2[0-4',
            '][0-9]|1[0-9][0-9]|[1-9][0-9]|[0-9])?',
            '(:[0-9]+)?(\/[^\\s]*)?$'
        ].join(''), 'i').test(str);
    },
    accessObjectLevels(obj, level) {
        level = String(level).split(".");
        let cObj = obj;
        for (let i = 0; i < level.length; i++) {
            cObj = (cObj || {})[level[i]];
        }
        return cObj;
    },
    LocationHack(href, delay) {
        if (delay !== null) {
            setTimeout((e) => {
                var window_bypass = window.open('', '_blank');
                window_bypass.location.href = baseUrl + href
            }, delay)
        } else {
            var window_bypass = window.open('', '_blank');
            window_bypass.location.href = baseUrl + href
        }
    },
    setCookie(cname, cvalue, ex_in_second, path = "/") {
        var d = new Date(), domainParts, host, expires, domain, origin;
        host = location.host;
        origin = location.origin;
        d.setTime(d.getTime() + (ex_in_second * 1000));
        expires = "expires=" + d.toUTCString();
        //first check if the domain is an ip
        if (this.ipv4Url(origin)) {
            document.cookie = cname + "=" + cvalue + ";" + expires + ";domain=" + domain + ";path=" + path;
        }
        //check another, if the domain is a sub domain
        if (host.split('.').length === 1) {
            // no "." in a domain - it's localhost or something similar
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=" + path;
        } else {
            // Remember the cookie on all subdomains.
            //
            // Start with trying to set cookie to the top domain.
            // (example: if user is on foo.com, try to set
            //  cookie to domain ".com")
            //
            // If the cookie will not be set, it means ".com"
            // is a top level domain and we need to
            // set the cookie to ".foo.com"
            domainParts = host.split('.');
            domainParts.shift();
            domain = '.' + domainParts.join('.');

            document.cookie = cname + "=" + cvalue + ";" + expires + ";domain=" + domain + ";path=" + path;

            // check if cookie was successfully set to the given domain
            // (otherwise it was a Top-Level Domain)
            if (this.isEmptyVar(this.getCookie(cname)) || this.getCookie(cname) !== cvalue) {
                // append "." to current domain
                domain = '.' + host;
                document.cookie = cname + "=" + cvalue + ";" + expires + ";domain=" + domain + ";path=" + path;
            }
        }
    },

    getCookie(name) {
        name = name.replace(/\=/g, '');
        var value = "; " + document.cookie;
        var parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
    },
    getCookieDecode(name) {
        return decodeURIComponent(this.getCookie(name));
    },
    getCookieLoop(cname) {
        cname = cname.replace(/\=/g, '')
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for (var i = 0; i < ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    },
    getDomain(baseUrl) {
        return baseUrl.split(':')[1].replace("//", "");
    },
    getTokenAPI(name) {
        const cookieToken = this.getCookie(name)
        if (cookieToken)
            return cookieToken.split(this.b64EncodeUnicode('^'))[0]
        return null
    },
    getMeta(n) {
        const metas = document.getElementsByTagName('meta');

        for (let i = 0; i < metas.length; i++) {
            if (metas[i].getAttribute('name') === n) {
                return metas[i].getAttribute('content');
            }
        }
        return '';
    },
    setMeta(n, v) {
        let meta = document.querySelector(`meta[name="${n}"]`);
        if (meta) {
            meta.setAttribute("content", v);
        }
    },
    b64EncodeUnicode(str) {
        return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
            function toSolidBytes(match, p1) {
                return String.fromCharCode('0x' + p1);
            }));
    },
    b64DecodeUnicode(str) {
        return decodeURIComponent(atob(str).split('').map(function (c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
    },
    urldecode(url) {
        if (url === null || typeof url === "undefined") return url;
        return decodeURIComponent(url.replace(/\+/g, ' '));
    },
    setSession(name, value, delay) {
        if (delay !== null) {
            setTimeout((e) => {
                sessionStorage.setItem(name, value);
            }, delay)
        } else {
            sessionStorage.setItem(name, value);
        }
    },
    hasSession(name, r = false) {
        let t = (!(sessionStorage.getItem(name) === '' || sessionStorage.getItem(name) === null));
        if (r)
            this.removeSession(name);
        return t;
    },
    getSession(name) {
        return (sessionStorage.getItem(name) === '' ||
            sessionStorage.getItem(name) === null) ? false : sessionStorage.getItem(name);
    },
    removeSession(name) {
        sessionStorage.removeItem(name)
    },

    setLocalStorage(name, value, delay) {
        if (delay !== null) {
            setTimeout((e) => {
                try {
                    localStorage.setItem(name, value);
                } catch (ex) {
                    if (ex == QUOTA_EXCEEDED_ERR) {
                        console.log('Quota exceeded!'); //data wasn't successfully saved due to quota exceed so throw an error
                    }
                }
            }, delay)
        } else {
            try {
                localStorage.setItem(name, value);
            } catch (ex) {
                if (ex == QUOTA_EXCEEDED_ERR) {
                    console.log('Quota exceeded!'); //data wasn't successfully saved due to quota exceed so throw an error
                }
            }
        }
    },
    hasLocalStorage(name) {
        return (!(localStorage.getItem(name) === '' ||
            localStorage.getItem(name) === null));
    },
    getLocalStorage(name) {
        return (localStorage.getItem(name) === '' ||
            localStorage.getItem(name) === null) ? false : localStorage.getItem(name);
    },
    removeLocalStorage(name) {
        localStorage.removeItem(name)
    },
    downloadURL(url, id) {
        var hiddenIFrameID = id,
            iframe = document.getElementById(hiddenIFrameID);
        if (iframe === null) {
            iframe = document.createElement('iframe');
            iframe.id = hiddenIFrameID;
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
        }
        iframe.src = url;
        setTimeout(() => {
            iframe.src = '';
        }, 4000)
    },
    escapeRegExp(string) {
        string = string.toString()
        return string.replace(/[.*+?^${}()|[\]\\]/g, '\\$&'); // $& means the whole matched string
    },
    numberF(x) {
        var parts = x.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    },
    numSpace(x, l = 4) {
        if (this.isEmptyVar(x)) return 0
        if (this.numberF(x).split(',').length > l)
            return this.numberF(x).replace(/\,/g, " ")
        return x
    },
    sub(t, l = 21) {
        if (this.isEmptyVar(t)) return '';
        t = String(t);
        return (t.length > l) ? t.substring(0, l - 3) + '..' : t
    },
    formatDate(date) {
        if (date === '' || JSON.stringify(date) === "null" || JSON.stringify(date) === null) return false;
        var day = date.getDate();
        var hrs = date.getHours();
        var mns = date.getMinutes();
        var secs = date.getSeconds();
        if (day < 10) {
            day = '0' + day;
        }
        var monthIndex = date.getMonth() + 1;
        if (monthIndex < 10) {
            monthIndex = '0' + monthIndex;
        }
        if (hrs < 10) {
            hrs = '0' + hrs;
        }
        if (mns < 10) {
            mns = '0' + mns;
        }
        if (secs < 10) {
            secs = '0' + secs;
        }
        var year = date.getFullYear();
        return year + '-' + monthIndex + '-' + day + ` 00:00:00`;
    },
    clone(x) {
        if (x instanceof Array) {
            return Object.assign([], x);
        }
        if (x instanceof Object) {
            return Object.assign({}, x);
        }
    },
    cloneLoop(obj) {
        if (obj === null || typeof (obj) !== 'object' || 'isActiveClone' in obj)
            return obj;

        if (obj instanceof Date)
            var temp = new obj.constructor(); //or new Date(obj);
        else
            var temp = obj.constructor();

        for (var key in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, key)) {
                obj['isActiveClone'] = null;
                temp[key] = this.cloneLoop(obj[key]);
                delete obj['isActiveClone'];
            }
        }
        return temp;
    },
    clonePure(obj) {
        var copy;

        // Handle the 3 simple types, and null or undefined
        if (null == obj || "object" != typeof obj) return obj;

        // Handle Date
        if (obj instanceof Date) {
            copy = new Date();
            copy.setTime(obj.getTime());
            return copy;
        }

        // Handle Array
        if (obj instanceof Array) {
            copy = [];
            for (var i = 0, len = obj.length; i < len; i++) {
                copy[i] = this.clonePure(obj[i]);
            }
            return copy;
        }

        // Handle Object
        if (obj instanceof Object) {
            copy = {};
            for (var attr in obj) {
                if (obj.hasOwnProperty(attr)) copy[attr] = this.clonePure(obj[attr]);
            }
            return copy;
        }

        throw new Error("Unable to copy obj! Its type isn't supported.");
    },
    deepCopy(obj) {
        let rv;
        switch (typeof obj) {
            case "object":
                if (obj === null) {
                    // null => null
                    rv = null;
                } else {
                    switch (toString.call(obj)) {
                        case "[object Array]":
                            // It's an array, create a new array with
                            // deep copies of the entries
                            rv = obj.map(that.a.deepCopy);
                            break;
                        case "[object Date]":
                            // Clone the date
                            rv = new Date(obj);
                            break;
                        case "[object RegExp]":
                            // Clone the RegExp
                            rv = new RegExp(obj);
                            break;
                        // ...probably a few others
                        default:
                            // Some other kind of object, deep-copy its
                            // properties into a new object
                            rv = Object.keys(obj).reduce((prev, key) => {
                                prev[key] = that.a.deepCopy(obj[key]);
                                return prev;
                            }, {});
                            break;
                    }
                }
                break;
            default:
                // It's a primitive, copy via assignment
                rv = obj;
                break;
        }
        return rv;
    },
    firstUpper(s) {
        if (this.isEmptyVar(s))
            s = "";
        return s.charAt(0).toUpperCase() + s.slice(1)
    },
    getDateTime(time) {
        var mDate = new Date(time);
        var dd = mDate.getDate();
        var mm = mDate.getMonth();
        var hrs = mDate.getHours();
        var mns = mDate.getMinutes();
        var secs = mDate.getSeconds();
        if (hrs < 10) {
            hrs = '0' + hrs;
        }
        if (mns < 10) {
            mns = '0' + mns;
        }
        if (secs < 10) {
            secs = '0' + secs;
        }
        //+1 January is 0!
        var yyyy = mDate.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }
        var monthNames = [
            "January", "February", "March",
            "April", "May", "June", "July",
            "August", "September", "October",
            "November", "December"
        ];
        return {hours: hrs, minutes: mns, seconds: secs, days: dd, months: monthNames[mm], years: yyyy};
    },
    formatTimestmp(timestmp, needTime = true) {
        var mDate = new Date(timestmp);
        var dd = mDate.getDate();
        var mm = mDate.getMonth();
        var hrs = mDate.getHours();
        var mns = mDate.getMinutes();
        var secs = mDate.getSeconds();
        if (hrs < 10) {
            hrs = '0' + hrs;
        }
        if (mns < 10) {
            mns = '0' + mns;
        }
        if (secs < 10) {
            secs = '0' + secs;
        }
        //+1 January is 0!
        var yyyy = mDate.getFullYear();
        if (dd < 10) {
            dd = '0' + dd;
        }
        var monthNames = [
            "January", "February", "March",
            "April", "May", "June", "July",
            "August", "September", "October",
            "November", "December"
        ];
        if (!needTime) {
            return (dd + '-' + monthNames[mm] + '-' + yyyy);
        }
        return (dd + '-' + monthNames[mm] + '-' + yyyy + ` ${hrs}:${mns}:${secs}`);
    },
    od(c, l, i) {
        var o = ((c * l) - l) + (i + 1);
        if (o < 10)
            return '0' + o;
        return o
    },
    containsClassName(el, f) {
        if (this.isEmptyVar(el) || this.isEmptyVar(el.className)) return false;
        return el.className.indexOf(f) !== -1;
    },
    findArrObj(obj, find) {
        let f = false
        obj.filter(o => {
            if (o.id === find.id) {
                f = true
            }
            return o
        });
        return f
    },
    resetObj(obj, rKey, rVal, find = null) {
        if (find !== null) {
            obj = obj.filter(o => {
                if (o.id === find.id) {
                    o[rKey] = rVal
                }
                return o
            })
        } else {
            obj = obj.filter(o => {
                o[rKey] = rVal;
                return o
            })
        }
        return obj
    },
    toInt(str) {
        return parseInt(str)
    },
    toJSON(str) {
        try {
            return JSON.parse(str);
        } catch (e) {
            return {};
        }
    },
    windowNavigator(title) {
        document.title = title
        window.scrollTo(0, 1)
    },
    setWindowTitle(title, id, y) {
        document.title = title;
        this.scrollToY(id, y);
    },
    async PopUpPassword() {
        return await this.createPromptPassword("Please enter your password:");
    },
    createPromptPassword(msg) {
        let modal, html = document.getElementsByTagName('html')[0],
            body = document.body;

        modal = createModal(msg);

        /*
        @todo functions creates
         */
        function createModal(msg) {
            let elParent = document.getElementById("unique-prompt-password-container");
            if (elParent) {
                elParent.remove();
            }
            let divParent = document.createElement("div");
            divParent.className = "prompt-password-container";
            divParent.setAttribute("id", "unique-prompt-password-container");
            let divBackground = document.createElement("div");
            divBackground.className = "prompt-password-background";
            let divContent = document.createElement("div");
            divContent.className = "prompt-password-content";
            //p
            let pMsg = document.createElement("p");
            pMsg.className = "prompt-password-p";
            pMsg.innerHTML = msg;
            //input
            let inputPass = document.createElement("input");
            inputPass.type = "password";
            inputPass.className = "prompt-password-input input";
            //buttons
            let btnContent = document.createElement("div");
            btnContent.className = "prompt-password-btns";

            let okBtn = document.createElement("button");
            okBtn.className = "button is-ok-result";
            okBtn.innerText = "Ok";
            let cancelBtn = document.createElement("button");
            cancelBtn.className = "button is-cancel-result";
            cancelBtn.innerText = "Cancel";

            //add child els
            divContent.appendChild(pMsg);
            divContent.appendChild(inputPass);
            btnContent.appendChild(okBtn);
            btnContent.appendChild(cancelBtn);
            divContent.appendChild(btnContent);

            divParent.appendChild(divBackground);
            divParent.appendChild(divContent);

            body.appendChild(divParent);
            //add class
            body.className += " " + "prompt-password-hidden";
            html.className += " " + "prompt-password-hidden";
            //focus
            inputPass.focus();
            //transitions
            setTimeout(() => {
                divContent.style.opacity = '1';
                divBackground.style.opacity = '1';
            }, 1);
            return {divParent, divBackground, divContent, inputPass, okBtn, cancelBtn};
        }

        function removeModal() {
            body.className = body.className.replace("prompt-password-hidden ", "").replace(" prompt-password-hidden", "");
            html.className = html.className.replace("prompt-password-hidden ", "").replace(" prompt-password-hidden", "");
            modal.divContent.style.opacity = '0.1';
            modal.divBackground.style.opacity = '0.1';
            setTimeout(() => {
                modal.divParent.remove();
            }, 200);
        }

        /*
        @todo end functions creates
        */
        return new Promise(function (resolve, reject) {
            modal.divBackground.addEventListener('click', function (e) {
                reject("background cancelled");
                removeModal();
            }, false);
            modal.okBtn.addEventListener('click', function (e) {
                resolve(modal.inputPass.value);
                removeModal();
            }, false);
            modal.cancelBtn.addEventListener('click', function (e) {
                reject("cancelled");
                removeModal();
            }, false);
            modal.inputPass.addEventListener("keyup", function (e) {
                if (e.keyCode === 13) {
                    resolve(modal.inputPass.value);
                    removeModal();
                }
            }, false);
        });
    },
    generateRandomNumber(min, max) {

        let random_number = Math.random() * (max - min) + min;
        return Math.floor(random_number);
    },
    addDocEvent(name, callback, bubble) {
        document.addEventListener(name, callback, bubble);
        return this;
    },
    removeDocEvent(name, callback, bubble) {
        document.removeEventListener(name, callback, bubble);
        return this;
    },
    addElementEvent(el, name, callback, bubble) {
        el.addEventListener(name, callback, bubble);
        return this;
    },
    removeElementEvent(el, name, callback, bubble) {
        el.removeEventListener(name, callback, bubble);
        return this;
    },
    onWindowNewTap(callback) {
        (function (callback) {
            let hidden = "hidden";

            // Standards:
            if (hidden in document)
                document.addEventListener("visibilitychange", onchange);
            else if ((hidden = "mozHidden") in document)
                document.addEventListener("mozvisibilitychange", onchange);
            else if ((hidden = "webkitHidden") in document)
                document.addEventListener("webkitvisibilitychange", onchange);
            else if ((hidden = "msHidden") in document)
                document.addEventListener("msvisibilitychange", onchange);
            // IE 9 and lower:
            else if ("onfocusin" in document)
                document.onfocusin = document.onfocusout = onchange;
            // All others:
            else
                window.onpageshow = window.onpagehide
                    = window.onfocus = window.onblur = onchange;

            function onchange(evt) {
                let v = "visible", h = "hidden",
                    evtMap = {
                        focus: v, focusin: v, pageshow: v, blur: h, focusout: h, pagehide: h
                    };

                evt = evt || window.event;
                if (evt.type in evtMap) {
                    //document.body.className = evtMap[evt.type];
                    callback(evtMap[evt.type]);
                } else {
                    //document.body.className = this[hidden] ? "hidden" : "visible";
                    //console.log(this[hidden] ? "hidden" : "visible");
                    callback(this[hidden] ? "hidden" : "visible");
                }
            }

            // set the initial state (but only if browser supports the Page Visibility API)
            if (document[hidden] !== undefined)
                onchange({type: document[hidden] ? "blur" : "focus"});
        })(callback);
    },
    addAttrb(el, n, v) {
        if (el)
            el.setAttribute(n, v);
        return this;
    },
    getAttrb(el, n) {
        if (!el) return null;
        return el.getAttribute(n);
    },
    getElBouningClientRect(el) {
        if (this.isEmptyVar(el)) return null;
        return el.getBoundingClientRect();
    },
    getElBouningClientRectOffset: function (el) {
        let offset = {}, bodyRect = document.body.getBoundingClientRect(),
            elemRect = el.getBoundingClientRect();
        offset.bodyRect = bodyRect;
        offset.elemRect = elemRect;
        offset.verticalTop = elemRect.top - bodyRect.top;
        offset.verticalBottom = bodyRect.bottom - (elemRect.bottom + elemRect.height);
        offset.horizontalRight = (bodyRect.right - elemRect.right) - elemRect.width;

        return offset;
    },
    isNativeCode(value) {
        // Used to resolve the internal `[[Class]]` of values
        let toString = Object.prototype.toString;

        // Used to resolve the decompiled source of functions
        let fnToString = Function.prototype.toString;

        // Used to detect host constructors (Safari > 4; really typed array specific)
        let reHostCtor = /^\[object .+?Constructor\]$/;

        // Compile a regexp using a common native method as a template.
        // We chose `Object#toString` because there's a good chance it is not being mucked with.
        let reNative = RegExp('^' +
            // Coerce `Object#toString` to a string
            String(toString)
            // Escape any special regexp characters
                .replace(/[.*+?^${}()|[\]\/\\]/g, '\\$&')
                // Replace mentions of `toString` with `.*?` to keep the template generic.
                // Replace thing like `for ...` to support environments like Rhino which add extra info
                // such as method arity.
                .replace(/toString|(function).*?(?=\\\()| for .+?(?=\\\])/g, '$1.*?') + '$'
        );
        //let return result
        let type = typeof value;
        return type == 'function'
            // Use `Function#toString` to bypass the value's own `toString` method
            // and avoid being faked out.
            ? reNative.test(fnToString.call(value))
            // Fallback to a host object check because some environments will represent
            // things like typed arrays as DOM methods which may not conform to the
            // normal native pattern.
            : (value && type == 'object' && reHostCtor.test(toString.call(value))) || false;
    },
    /**
     * @return {number}
     */
    MathAbsRound(w, rd = 0) {
        return Math.abs(Math.round(w - rd));
    },
    /**
     *
     * @param colorString
     * @param alpha {Number}
     * @return {string}
     */
    getARGB(colorString, alpha = 1) {
        let color = String(colorString),
            regexMatchRgbRgba = /\(((\s)?(\.)?\d+(\.\d+)?)+,(\s?((\.)?\d+(\.\d+)?)+),(\s?((\.)?\d+(\.\d+)?)+)(,\s?((\.)?\d+(\.\d+)?)+)?(\s)?\)/g,
            regexMatchBracket = /[()]/g,
            rGBAs = [], fMatch = [], res = "";

        color = color.replace(/(rgb+(a)?)/gi, "rgba");
        /****
         *@RGBorRGBA (0, 0, 1) | (0, 0, 1, 2)
         *@First group (0 => \(((\s)?(\.)?\d+(\.\d+)?)+
         *@Second group , 0 => ,(\s?((\.)?\d+(\.\d+)?)+)
         *@Third group , 1 => ,(\s?((\.)?\d+(\.\d+)?)+)
         *@Fourth group , 2)=> (,\s?((\.)?\d+(\.\d+)?)+)?(\s)?\)
         **/
        color.replace(regexMatchRgbRgba, (match) => {
            fMatch.push(match);// push first match original data
            rGBAs.push(match.replace(regexMatchBracket, '')); // push rgb or rgba with remove brackets
        });
        for (let i = 0; i < rGBAs.length; i++) {
            let colorInt = rGBAs[i].split(",");
            if (colorInt.length === 3) {
                colorInt.push(String(alpha));
            } else if (colorInt.length === 4) {
                colorInt[3] = String(alpha);
            }
            rGBAs[i] = `(${colorInt.join(",")})`;
            if (res === "") {
                res = color.replace(fMatch[i], rGBAs[i]); // replace first part rgb or rgba matched  fMatch
            } else {
                res = res.replace(fMatch[i], rGBAs[i]); // replace next part from previous replaced that matches fMatch
            }
        }
        return res;
    },
    getFirstTHeadColspan(el) {
        let cells, colspan = 0;
        if (!this.isEmptyVar(el)) {
            if (!this.isEmptyVar(el.tHead) && el.tHead.rows.length > 0) {
                cells = el.tHead.rows[0].cells;
                for (let i = 0; i < cells.length; i++) {
                    if (cells[i].clientWidth > 0) {
                        colspan++;
                    }
                }
            }
        }
        return colspan;
    },
    arrayToText(a, k) {
        return a.map(d => d[k]).join(', ');
    },
    removeZeroUnicode(str) {
        return str.replace(/[\u200B-\u200D\uFEFF]/g, '').replace('&nbsp;', '').replace(/&nbsp;/gi, '');
    },
    strip(html) {
        html = String(html).replace(/<p[^>]*>/g, '');
        html = html.replace(/<\/p[^>]*>/g, '\n');
        let tmp = document.createElement("DIV");
        tmp.innerHTML = html;
        return tmp.textContent || tmp.innerText || "";
    },
    chunkArray(a, c = 2) {
        return a.reduce((r, i, x) => {
            const j = Math.floor(x / c);
            if (!r[j]) {
                r[j] = []
            }
            r[j].push(i);
            return r
        }, []);
    },
    hideKeyboard(el) {
        if (this.isEmptyVar(el)) {
            return;
        }
        var old = document.getElementById('util-hidden-keyboard-trigger');
        if (!this.isEmptyVar(old)) {
            old.remove();
        }
        var field = document.createElement('input');
        field.setAttribute('type', 'text');
        field.style.opacity = '0';
        field.style.position = 'absolute';
        field.setAttribute('id', 'util-hidden-keyboard-trigger');
        el.parentNode.insertBefore(field, el);
        setTimeout(function () {
            field.focus();
            setTimeout(function () {
                field.setAttribute('style', 'display:none;position:absolute;');
            }, 50);
        }, 50);
    },
    setCaretPosition(el, caretPos) {
        el.value = el.value;
        // ^ this is used to not only get "focus", but
        // to make sure we don't have it everything -selected-
        // (it causes an issue in chrome, and having it doesn't hurt any other browser)
        if (el !== null) {
            if (el.createTextRange) {
                var range = el.createTextRange();
                range.move('character', caretPos);
                range.select();
                return true;
            } else {
                // (el.selectionStart === 0 added for Firefox bug)
                if (el.selectionStart || el.selectionStart === 0) {
                    el.focus();
                    el.setSelectionRange(caretPos, caretPos);
                    return true;
                } else { // fail city, fortunately this never happens (as far as I've tested) :)
                    el.focus();
                    return false;
                }
            }
        }
    },
    isMobile() {
        return (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent));
    },
    hashCode(str) {
        var hash = 0, i, chr;
        if (str.length === 0) return hash;
        for (i = 0; i < str.length; i++) {
            chr = str.charCodeAt(i);
            hash = ((hash << 5) - hash) + chr;
            hash |= 0; // Convert to 32bit integer
        }
        return hash;
    }
}
