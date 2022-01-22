export class UnfitUserObject extends Error {

    constructor() {
        super("This user object is not fit for authentication");
    }

}
